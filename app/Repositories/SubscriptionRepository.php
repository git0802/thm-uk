<?php
namespace App\Repositories;

use App\CouponSet;

class SubscriptionRepository
{
    private $price, $sale, $trial, $period, $notify, $month;

    private static $data = [
        [
            'price' => 15,
            'amount' => 15,
            'period' => [
                'interval' => 'month',
                'count'    => 1
            ],
            'title' => 'Hot Step',
            'sale' => [
                'value' => 0,
                'price' => 15,
                'amount' => 15,
                'symbol' => '%',
                'str' => '',
            ]
        ],
        [
            'price' => 15,
            'amount' => 45,
            'period' => [
                'interval' => 'three months',
                'count'    => 3
            ],
            'title' => 'Hot Run',
            'sale' => [
                'value' => 0,
                'price' => 15,
                'amount' => 45,
                'symbol' => '%',
                'str' => '',
            ]
        ],
        [
            'price' => 15,
            'amount' => 60,
            'period' => [
                'interval' => 'six months',
                'count'    => 6
            ],
            'title' => 'Hot Sizzle',
            'sale' => [
                'value' => 33.5,
                'price' => 10,
                'amount' => 60,
                'symbol' => '%',
                'str' => 'SAVE 33%',
            ]
        ],
        [
            'price' => 15,
            'amount' => 72,
            'period' => [
                'interval' => 'year',
                'count'    => 12
            ],
            'title' => 'Hot Year',
            'sale' => [
                'value' => 60,
                'price' => 6,
                'amount' => 72,
                'symbol' => '%',
                'str' => 'SAVE 60%',
            ]
        ],
    ];

    public function __construct()
    {
        $this->price  = 5.75;
        $this->sale   = 0;
        $this->trial  = 14;
        $this->month  = 30;
        $this->period = [
            'interval' => 'year',
            'count'    => 12
        ];
        $this->notify = [
            'expire_days' => 3
        ];

    }

    private function amount($unit) {
        return $this->price * $this->getPeriod('count') * $unit;
    }

    private function amountWithSubscription($unit, $subscription){
        return $subscription['price'] * $subscription['period']['count'] * $unit;
    }

    public function getPrice($unit)
    {
        $amount = $this->amount($unit);

        $discountAmount = ($amount / 100) * $this->sale;
        $discountAmount = floor($discountAmount / $unit) * $unit;

        return (int)($amount - $discountAmount);
    }

    public function getPriceWithSubscription($unit, $subscription){
        $amount = $this->amountWithSubscription($unit, $subscription);

        $discountAmount = ($amount / 100) * $subscription['sale']['value'];
        $discountAmount = floor($discountAmount / $unit) * $unit;

        return (int)($amount - $discountAmount);
    }

    public function getPeriod($key)
    {
        return $this->period[$key];
    }

    public function getSubscriptions(){
        return self::$data;
    }

    public function getData($key)
    {
        if ( $key ) {
            return $this->{$key};
        }else{
            return [
                'amount' => $this->getPrice(1),
                'period' => $this->period,
                'data'   => [
                    'price' => $this->price,
                    'sale'  => $this->sale,
                    'trial' => $this->trial,
                    'month' => $this->month
                ]
            ];
        }
    }

    public function getSubscriptionRepo($name){
        foreach (self::$data as $datum) {
            if($name == $datum['title']){
                return $datum;
            }
        }
    }

    public function calcSubscriptionWithCoupon($subscription, $coupon){
        $sale = $subscription['sale'];

        if ($coupon) {
            $set   = $coupon->set;
            $price = $set->cost;

            switch ($set->type) {
                case 'fixed':
                    if ($set->name !== 'default') {
                        $sale['value'] = $set->value;
                        $sale['symbol'] = config('thehotmeal.currencySm');
                    }
                    break;
                case 'percent':
                    $sale['value'] = $set->value;
                    break;
                case 'month':
                    $price = $this->getPrice(1);
                    break;
            }
        }else{
            $query = CouponSet::default()->first();
            $price = $query->cost;
        }

        $sale['str'] = $sale['value'] . $sale['symbol'];

        return [
            'amount' => $price,
            'sale'   => $sale
        ];

    }

    public function calcSubscription($coupon) {
        $sale = [
            'value'  => $this->getData('sale'),
            'symbol' => '%'
        ];

        if ($coupon) {
            $set   = $coupon->set;
            $price = $set->cost;

            switch ($set->type) {
                case 'fixed':
                    if ($set->name !== 'default') {
                        $sale['value'] = $set->value;
                        $sale['symbol'] = config('thehotmeal.currencySm');
                    }
                    break;
                case 'percent':
                    $sale['value'] = $set->value;
                    break;
                case 'month':
                    $price = $this->getPrice(1);
                    break;
            }
        }else{
            $query = CouponSet::default()->first();
            $price = $query->cost;
        }

        $sale['str'] = $sale['value'] . $sale['symbol'];

        return [
            'amount' => $price,
            'sale'   => $sale
        ];
    }
}
