<?php

namespace App\Console\Commands;

use App\CouponSet;
use App\Repositories\SubscriptionRepository;
use App\Setting;
use App\SubscriptionPlan;
use App\Helpers\LocationHelper;
use Illuminate\Console\Command;
use Stripe\Exception\ApiErrorException;
use Stripe\Exception\InvalidRequestException;
use Stripe\StripeClient;

class SetStripe extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'set_stripe {--subscription-only= : Set up subscription only}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set Stripe Settings';

    /**
     * Execute the console command.
     *
     * @param SubscriptionRepository $subscription
     * @return void
     * @throws ApiErrorException
     */
    public function handle(SubscriptionRepository $subscription)
    {
        $this->info("Creating default product...");

        $coupon = CouponSet::where('name', 'default');

        if ( $coupon->exists() ) {
            $coupon->delete();
        }

        $currency = LocationHelper::getCurrency();

        $set = CouponSet::create([
            'name'                => 'default',
            'type'                => 'fixed',
            'value'               => $subscription->getPeriod('count'),
            'cost'                => $subscription->getPrice(1),
            'title'               => 'Default subscription',
            'deletion_prohibited' => true
        ]);

        $stripe = new StripeClient( env('STRIPE_API_KEY') );

        try {
            $stripeProduct = $stripe->products->create( ['name' => $set->title] );

            $set->stripe_product_id = $stripeProduct->id;

            $stripePrice = $stripe->prices->create([
                'unit_amount' => $subscription->getPrice(100),
                'currency'    => strtolower($currency['currency']),
                'product'     => $set->stripe_product_id,
                'recurring' => [
                    'interval' => $subscription->getPeriod('interval')
                ]
            ]);
            $set->stripe_price_id = $stripePrice->id;
            $set->save();
        } catch (InvalidRequestException $exception) {
            $set->delete();
            $this->error($exception->getMessage());
        }

        $this->info("Creating default subscription plans ...");

        $subscriptionPlans = $subscription->getSubscriptions();

        foreach ($subscriptionPlans as $subscriptionPlan){
            $plan = SubscriptionPlan::where('name', $subscriptionPlan['title']);
            if($plan->exists()){
                $plan->delete();
            }
            $plan = SubscriptionPlan::create([
                'name'                => $subscriptionPlan['title'],
                'title'               => $subscriptionPlan['title'],
                'price'               => $subscriptionPlan['price'],
                'months'              => $subscriptionPlan['period']['count'],
                'sale'                => $subscriptionPlan['sale']['value'],
                'amount'              => $subscriptionPlan['sale']['amount'],
                'price_sale'          => $subscriptionPlan['sale']['price'],
            ]);
            try {
                $stripeProduct = $stripe->products->create( ['name' => $plan->title] );

                $plan->stripe_product_id = $stripeProduct->id;

                $stripePrice = $stripe->prices->create([
                    'unit_amount' => $subscription->getPriceWithSubscription(100, $subscriptionPlan),
                    'currency'    => strtolower($currency['currency']),
                    'product'     => $plan->stripe_product_id,
                    'recurring' => [
                        'interval' => 'month',
                        'interval_count' => $subscriptionPlan['period']['count']
                    ]
                ]);
                $plan->stripe_price_id = $stripePrice->id;
                $plan->save();
            } catch (InvalidRequestException $exception) {
                $plan->delete();
                $this->error($exception->getMessage());
            }
        }

        $this->info("Default product created successfully!");

        if (!$this->option('subscription-only')) {
            $this->info("Creating Stripe Webhook...");

            $webhook = Setting::where('name', 'stripe-webhook');

            if ( $webhook->exists() ) {
                $webhook->delete();
            }

            try {
                $set_hook = $stripe->webhookEndpoints->create([
                    'url'            => route('stripe-check'),
                    'enabled_events' => ['charge.succeeded']
                ]);

                Setting::create([
                    'name'  => 'stripe-webhook',
                    'value' => $set_hook->secret
                ]);
            } catch (InvalidRequestException $exception) {
                $this->error($exception->getMessage());
            }

            $this->info("Stripe Webhook created successfully!");
        }
    }
}
