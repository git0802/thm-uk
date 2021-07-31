<?php

namespace App\Http\Controllers\Auth;

use App\CouponCode;
use App\CouponSet;
use App\Exceptions\PaymentException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Mail\ActivateUser;
use App\Repositories\SubscriptionRepository;
use App\SubscriptionPlan;
use App\User;
use App\Subscription;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Stripe\Exception\ApiErrorException;
use Stripe\Exception\InvalidRequestException;
use Stripe\StripeClient;

/**
 * Register controller.
 *
 * @package App\Http\Controllers\Auth
 */
class RegisterController extends Controller
{
    /**
     * Validated request.
     *
     * @var RegisterRequest
     */
    protected $request;

    /**
     * New user.
     *
     * @var User
     */
    protected $user, $payment, $stripe;


    public function __construct()
    {
        $this->stripe = new StripeClient(env('STRIPE_API_KEY'));
    }

    public function __invoke(RegisterRequest $request, SubscriptionRepository $subscription)
    {
        $this->request = $request;

        $this->handleCoupon();
        $result = $this->saveUser($subscription);

        return response($result, $result['done'] ? 200 : 422);
    }

    /**
     * @param RegisterRequest $request
     * @throws ApiErrorException
     * @throws PaymentException
     * @return Response
     */
    public function checkCart(RegisterRequest $request) {
        try {
            $paymentMethods = $this->stripe->paymentMethods->create([
                'type' => 'card',
                'card' => $request->only([
                    'number', 'exp_month', 'exp_year', 'cvc'
                ])
            ]);

            $customers = $this->stripe->customers->create([
                'name' => $request->get('name') .' '. $request->get('last_name'),
                'email' => $request->get('email'),
            ]);

            $intents = $this->stripe->setupIntents->create([
                'customer' => $customers->id
            ]);

            $response = $this->stripe->setupIntents->confirm($intents->id, [
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
     * Check coupon and attach it to the user.
     *
     * @return void
     */
    private function handleCoupon(): void
    {
        $this->coupon = CouponCode::where('code', $this->request->coupon)->first();
    }

    /**
     * @param $subscription
     * @return array
     * @throws ApiErrorException
     * @throws PaymentException
     */
    private function saveUser($subscription)
    {
        try {
            $intents = $this->stripe->setupIntents->retrieve($this->request->verification);

            if ($intents->status !== 'succeeded') {
                return [
                    'done'    => false,
                    'result'  => 'error',
                    'message' => 'It\'s impossible to connect this card to your account. Please use another card and try again.'
                ];
            }else{
//                $set    = $this->coupon ? $this->coupon->set : CouponSet::default()->first();
                $trial  = $subscription->getData('trial');
                $subscriptionPlan = SubscriptionPlan::find($this->request->subscription);
                $start  = now()->addDays($trial);
                $ending = now()->addDays($trial);

                try {
                    $this->stripe->customers->update($intents->customer, [
                        'invoice_settings' => [
                            'default_payment_method' => $intents->payment_method
                        ]
                    ]);

                    $response = $this->stripe->subscriptions->create([
                        'trial_period_days' => $trial,
                        'off_session' => true,
                        'customer' => $intents->customer,
                        'items'    => [
                            ['price' => $subscriptionPlan->stripe_price_id]
                        ]
                    ]);

                    $this->user = User::make($this->request->all());
                    $this->user->password = Hash::make($this->user->password);
                    $this->user->activation_token = Str::random(50);
                    $this->user->goal = $this->request->initial_goal;
                    $this->user->save();

                    $this->payment = Subscription::make([
                        'subscription_plan_id' => $subscriptionPlan->id,
                        'subscription_id' => $response->id,
                        'payment_method'  => $intents->payment_method,
                        'customer_id'     => $intents->customer,
                        'user_id'         => $this->user->id,
                        'start'           => $start,
                        'ending'          => $ending,
                        'paid'            => (bool)$trial,
                    ]);

                    $this->payment->save();

                    if ($this->coupon) {
                        $this->coupon->user_id = $this->user->id;
                        $this->coupon->save();
                    }

                    Mail::to($this->user->email)->send(new ActivateUser($this->user));

                    return [
                        'done'    => true,
                        'result'  => 'success',
                        'message' => 'Please, confirm your email to continue.'
                    ];
                } catch (InvalidRequestException $exception) {
                    $this->stripe->customers->delete($intents->customer);
                    throw new PaymentException($exception->getMessage(), 422);
                }
            }
        } catch (InvalidRequestException $exception) {
            throw new PaymentException($exception->getMessage(), 422);
        }
    }
}
