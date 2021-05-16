<?php

use App\CouponCode;
use App\CouponSet;
use App\Repositories\SubscriptionRepository;
use App\Helpers\LocationHelper;
use Illuminate\Database\Seeder;
use Stripe\Exception\ApiErrorException;
use Stripe\Exception\InvalidRequestException;
use Stripe\StripeClient;

class CouponSetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @param SubscriptionRepository $subscription
     * @return void
     * @throws ApiErrorException
     */
    public function run(SubscriptionRepository $subscription)
    {
        $currency = LocationHelper::getCurrency();
        $interval = $subscription->getPeriod('interval');
        $price    = $subscription->getPrice(1);

        $sets = [
            [
                'name' => 'Subscription 1 month free',
                'type' => 'month',
                'value' => 1,
                'title' => 'Subscription 1 month trial',
                'cost' => $price
            ],[
                'name' => 'Subscription 3 month free',
                'type' => 'month',
                'value' => 3,
                'title' => 'Subscription 3 month trial',
                'cost' => $price
            ],
        ];

        foreach ($sets as $set) {
            $price  = (int) $set['cost'] * 100;
            $newSet = CouponSet::create($set);
            $stripe = new StripeClient(env('STRIPE_API_KEY'));

            try {
                $stripeProduct = $stripe->products->create([
                    'name' => $newSet->title,
                ]);
                $newSet->stripe_product_id = $stripeProduct->id;

                $stripePrice = $stripe->prices->create([
                    'currency'    => strtolower($currency['currency']),
                    'product'     => $newSet->stripe_product_id,
                    'unit_amount' => $price,
                    'recurring' => [
                        'interval' => $interval
                    ]
                ]);

                $newSet->stripe_price_id = $stripePrice->id;
                $newSet->save();
            } catch (InvalidRequestException $exception) {
                \Illuminate\Support\Facades\Log::info($exception->getMessage());
                $newSet->delete();
            }
            Factory(CouponCode::class, 100)->create([
                'coupon_set_id' => $newSet->id,
            ]);
        }
    }
}
