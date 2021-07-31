<?php

namespace App\Http\Controllers;

use App\CouponCode;
use App\CouponSet;
use App\Exceptions\Error;
use App\Exceptions\PaymentException;
use App\Http\Requests\RenewSubscription;
use App\Mail\SubscriptionCancelled;
use App\Mail\SubscriptionCreated;
use App\Mail\SubscriptionRenew;
use App\SubscriptionPlan;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Stripe\Exception\ApiErrorException;
use Stripe\Exception\InvalidRequestException;
use Stripe\StripeClient;
use App\Repositories\SubscriptionRepository;
use Illuminate\Http\Request;

/**
 * Subscription controller.
 *
 * @package App\Http\Controllers
 */
class SubscriptionController extends Controller
{
    /**
     * SubscriptionController constructor.
     * @param SubscriptionRepository $subscription
     */
    protected $subscription;

    public function __construct(SubscriptionRepository $subscription)
    {
        $this->middleware('auth:sanctum');
        $this->subscription = $subscription;
    }

    /**
     * Check subscription status.
     *
     * @param Request $request
     * @param SubscriptionRepository $subscription
     * @return Response
     */

    public function defaultPrice(Request $request, SubscriptionRepository $subscription)
    {
        $code = $request->get('coupon');

        if ( $code ) {
            $coupon = CouponCode::where('code', $code)->first();
        }

        $price = isset($coupon) ? $coupon->set : CouponSet::default()->first();
        $trial = $price->type === 'month' ? $subscription->getData('trial') + $price->value * 30 : 0;

        return response([
            'success' => true,
            'data' => [
                'price' => $price,
                'trial' => $trial
            ]
        ]);
    }

    public function status()
    {
        $user   = Auth::user();

        if (empty($user->subscription->ending)) {
            return response([
                'success' => true,
                'data' => [
                    'message' => 'Sorry, you have no active subscription',
                    'subscription' => false,
                    'is_cancelled' => true,
                    'is_expired' => true,
                ]
            ]);
        } else {
            $ending = [
                'days' => $user->subscription->ending->diffInDays(),
                'date' => $user->subscription->ending->format(config('thehotmeal.dateFormat')),
            ];

            $ending['days'] = $ending['days'] > 1 ? $ending['days'] . ' days' : $ending['days'] . ' day';

            switch ($user->subscription->paid) {
                case 0:
                    $msg = $user->subscription->payment_method
                        ? 'We couldn\'t make the payment with your billing information. Please fix this problem to have an access to the functionality of the service.'
                        : 'Sorry, you have no active subscription. The access was restricted. Please renew your subscription to have an access to THEHOTMEAL service.';
                    break;
                case 1:
                    if ($user->subscription->payment_method) {
                        $msg = 'You use the trial version. The fee for activating your next subscription will be taken on ' . $ending['date'];
                    } else {
                        $deadline = $user->subscription->ending->diff(now())->days;
                        $note = $deadline > 1 ? $deadline . ' days' : $deadline . ' day';
                        $msg = 'Your subscription is canceled. There are ' . $note . ' left until the trial period ends.';
                    }
                    break;

                case 2:
                    $msg = $user->subscription->payment_method
                        ? 'Your subscription is live. Subscription auto-renew will be on ' . $ending['date']
                        : 'Your subscription is canceled. There are ' . $ending['days'] . ' left until the paid period ends';

                    break;
            }
        }

        return response([
            'success' => true,
            'data' => [
                'message'      => $msg,
                'subscription' => (bool) $user->subscription->paid,
                'is_cancelled' => !(bool) $user->subscription->payment_method,
                'subscription_plan' => $user->subscription->subscriptionPlan,
                'is_expired'   => $user->subscription->ending < now(),
            ]
        ]);
    }

    /**
     * Cancel subscription.
     *
     * @return Response
     * @throws Error
     */
    public function cancel()
    {
        $user  = Auth::user();
        $stripe = new StripeClient(env('STRIPE_API_KEY'));

        try {
            $stripe->subscriptions->cancel(
                $user->subscription->subscription_id,
                []
            );
            $user->subscription->payment_method  = null;
            $user->subscription->subscription_id = null;

            $user->subscription->save();
        } catch (\Exception $exception) {
            throw new Error($exception->getMessage());
        }

        Mail::to($user->email)->send(new SubscriptionCancelled($user));

        return response([
            'success' => true,
            'message' => 'Subscription successfully canceled!'
        ]);
    }

    /**
     * @param RenewSubscription $request
     * @return Response
     * @throws PaymentException|ApiErrorException
     */

    public function renewCheck(RenewSubscription $request) {
        $user   = Auth::user();
        $stripe = new StripeClient(env('STRIPE_API_KEY'));

        try {
            $paymentMethods = $stripe->paymentMethods->create([
                'type' => 'card',
                'card' => $request->only([
                    'number', 'exp_month', 'exp_year', 'cvc'
                ])
            ]);

            $intents = $stripe->setupIntents->create([
                'customer' => $user->subscription->customer_id
            ]);

            $response = $stripe->setupIntents->confirm($intents->id, [
                'payment_method' => $paymentMethods->id,
                'return_url'     => url('pending-verification')
            ]);

            return response([
                'action' => $response->next_action,
                'result' => $response->status,
                'id'     => $response->id
            ]);
        } catch (InvalidRequestException $exception) {
            throw new PaymentException($exception->getMessage(), 422);
        }
    }

    /**
     * Renew subscription.
     *
     * @param RenewSubscription $request
     * @param bool $renew
     * @return Response
     * @throws ApiErrorException
     * @throws PaymentException
     */
    public function renew(RenewSubscription $request, $renew = true)
    {
        $user   = Auth::user();
        $stripe = new StripeClient(env('STRIPE_API_KEY'));

        try {
            $intents = $stripe->setupIntents->retrieve($request->verification);

            if ($intents->status !== 'succeeded' || $intents->customer !== $user->subscription->customer_id) {
                return response([
                    'done'    => false,
                    'result'  => 'error',
                    'message' => 'It\'s impossible to connect this card to your account. Please use another card and try again.'
                ]);
            }else{
                $coupon = CouponCode::where('code', $request->coupon)->first();

                if ($coupon) {
                    $coupon->user_id = $user->id;
                    $coupon->save();
                    $set   = $coupon ? $coupon->set : CouponSet::default()->first();
                    $now   = now();
                    $trial = $set->type === 'month' ? $this->subscription->getData('trial') + $set->value * 30 : 0;
                }
                else{
                    $trial = 0;
                }

//                $set   = $coupon ? $coupon->set : CouponSet::default()->first();
                $now   = now();
//                $trial = $set->type === 'month' ? $this->subscription->getData('trial') + $set->value * 30 : 0;

                if ( $user->subscription->ending > $now ) {
                    $diff = $user->subscription->ending->diff($now)->days;
                    $trial += ($diff + 1);
                }

                $subscriptionPlan = SubscriptionPlan::find($request->subscription);

                $ending = $now->addDays($trial);

                try {
                    $stripe->customers->update($intents->customer, [
                        'invoice_settings' => [
                            'default_payment_method' => $intents->payment_method
                        ]
                    ]);

                    $subscription = $stripe->subscriptions->create([
//                        'trial_period_days' => $trial,
                        'off_session' => true,
                        'customer' => $intents->customer,
                        'items'    => [
                            ['price' => $subscriptionPlan->stripe_price_id]
                        ]
                    ]);

                    $user->subscription->payment_method = $intents->payment_method;
                    $user->subscription->subscription_plan_id = $subscriptionPlan->id;
                    $user->subscription->subscription_id = $subscription->id;
                    $user->subscription->ending = $ending;
                    $user->subscription->paid = $user->subscription->paid == 2 ? $user->subscription->paid : (bool) $trial;
//                    $user->subscription->paid = $user->subscription->paid == 2 ;
                    $user->subscription->save();
                } catch (InvalidRequestException $exception) {
                    throw new PaymentException($exception->getMessage(), 422);
                }
            }
        }catch (InvalidRequestException $exception) {
            throw new PaymentException($exception->getMessage(), 422);
        }

        if ($renew) {
            Mail::to($user->email)->send(new SubscriptionRenew($user));
            $message = 'Subscription successfully renewed!';
        } else {
            Mail::to($user->email)->send(new SubscriptionCreated($user));
            $message = 'Subscription successfully created!';
        }

        return response([
            'done'    => true,
            'result'  => 'success',
            'message' => $message,
        ]);
    }

    public function create(RenewSubscription $request)
    {
        $user   = Auth::user();
        $stripe = new StripeClient(env('STRIPE_API_KEY'));
        try {
            $intents = $stripe->setupIntents->retrieve($request->verification);

            if ($intents->status !== 'succeeded') {
                return [
                    'done'    => false,
                    'result'  => 'error',
                    'message' => 'It\'s impossible to connect this card to your account. Please use another card and try again.'
                ];
            } else {
//                $set    = $this->coupon ? $this->coupon->set : CouponSet::default()->first();
                $coupon = CouponCode::where('code', $request->coupon)->first();
                if ($coupon) {
                    $coupon->user_id = $user->id;
                    $coupon->save();
                    $set   = $coupon ? $coupon->set : CouponSet::default()->first();
                    $now   = now();
                    $trial = $set->type === 'month' ? $this->subscription->getData('trial') + $set->value * 30 : 0;
                }
                else{
                    $trial  = $this->subscription->getData('trial');
                }

                $subscriptionPlan = SubscriptionPlan::find($request->subscription);
                $start  = now()->addDays($trial);
                $ending = now()->addDays($trial);

                try {
                    $stripe->customers->update($intents->customer, [
                        'invoice_settings' => [
                            'default_payment_method' => $intents->payment_method
                        ]
                    ]);

                    $response = $stripe->subscriptions->create([
                        'trial_period_days' => $trial,
                        'off_session' => true,
                        'customer' => $intents->customer,
                        'items'    => [
                            ['price' => $subscriptionPlan->stripe_price_id]
                        ]
                    ]);

                    $user->subscription->payment_method = $intents->payment_method;
                    $user->subscription->subscription_plan_id = $subscriptionPlan->id;
                    $user->subscription->subscription_id = $response->id;
                    $user->subscription->ending = $ending;
                    $user->subscription->paid = (bool)$trial;
//                    $user->subscription->paid = $user->subscription->paid == 2 ;
                    $user->subscription->save();


                    Mail::to($user->email)->send(new SubscriptionCreated($user));
                    $message = 'Subscription successfully created!';

                    return [
                        'done'    => true,
                        'result'  => 'success',
                        'message' => $message
                    ];
                } catch (InvalidRequestException $exception) {
                    throw new PaymentException($exception->getMessage(), 422);
                }
            }
        } catch (InvalidRequestException $exception) {
            throw new PaymentException($exception->getMessage(), 422);
        }
    }
}
