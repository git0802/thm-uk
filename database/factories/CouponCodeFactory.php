<?php

/** @var Factory $factory */

use App\CouponCode;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Support\Str;

$factory->define(CouponCode::class, function () {
    return [
        'code' => mb_strtoupper(Str::random(8)),
        'user_id' => null,
        'coupon_set_id' => 0,
        'used_at' => null,
    ];
});
