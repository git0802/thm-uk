<?php

namespace App\Observers;

use App\CouponCode;

class CouponCodeObserver
{
    /**
     * Handle the coupon code "created" event.
     *
     * @param CouponCode $couponCode
     * @return void
     */
    public function created(CouponCode $couponCode)
    {
        //
    }

    /**
     * Update used_at timestamp when user uses the code.
     *
     * @param CouponCode $couponCode
     * @return void
     */
    public function updated(CouponCode $couponCode)
    {
        if ($couponCode->user && !$couponCode->used_at) {
            $couponCode->used_at = now();
            $couponCode->save();
        }
    }

    /**
     * Handle the coupon code "deleted" event.
     *
     * @param CouponCode $couponCode
     * @return void
     */
    public function deleted(CouponCode $couponCode)
    {
        //
    }

    /**
     * Handle the coupon code "restored" event.
     *
     * @param CouponCode $couponCode
     * @return void
     */
    public function restored(CouponCode $couponCode)
    {
        //
    }

    /**
     * Handle the coupon code "force deleted" event.
     *
     * @param CouponCode $couponCode
     * @return void
     */
    public function forceDeleted(CouponCode $couponCode)
    {
        //
    }
}
