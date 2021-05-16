<?php

namespace App\Relationships\HasMany;

use App\CouponCode;

trait Codes
{
    public function codes()
    {
        return $this->hasMany(CouponCode::class);
    }
}
