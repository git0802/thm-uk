<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CouponSet extends Model
{
    protected $fillable = [
        'name', 'type', 'value', 'cost', 'title', 'deletion_prohibited'
    ];

    protected $hidden = [
        'stripe_product_id',
        'stripe_price_id',
    ];

    public function codes()
    {
        return $this->hasMany('App\CouponCode');
    }

    public function scopeDefault($query)
    {
        return $query->where('name', 'default');
    }
}
