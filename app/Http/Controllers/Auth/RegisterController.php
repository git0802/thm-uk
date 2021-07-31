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

        // $this->handleCoupon();
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
            $trial  = $subscription->getData('trial');
            $start  = now()->addDays($trial);
            $ending = now()->addDays($trial);

            $this->user = User::make($this->request->all());
            $this->user->password = Hash::make($this->user->password);
            $this->user->activation_token = Str::random(50);
            $this->user->goal = $this->request->initial_goal;
            $this->user->save();

            $customers = $this->stripe->customers->create([
                'name' => $this->user->name .' '. $this->user->last_name,
                'email' => $this->user->email,
            ]);

            $this->payment = Subscription::make([
                'subscription_plan_id' => null,
                'subscription_id' => null,
                'payment_method'  => null,
                'customer_id'     => $customers->id,
                'user_id'         => $this->user->id,
                'start'           => $start,
                'ending'          => $ending,
                'paid'            => (bool)$trial,
            ]);

            $this->payment->save();

            Mail::to($this->user->email)->send(new ActivateUser($this->user));

            return [
                'done'    => true,
                'result'  => 'success',
                'message' => 'Please, confirm your email to continue.'
            ];
        } catch (InvalidRequestException $exception) {
            throw new PaymentException($exception->getMessage(), 422);
        }
    }
}
