<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    protected $fillable = [
        'name', 'value', 'price', 'title', 'months', 'sale', 'amount', 'price_sale'
    ];

    protected $hidden = [
        'stripe_product_id',
        'stripe_price_id',
    ];

    public function subscription()
    {
        return $this->hasMany('App\Subscription', 'subscription_plan_id');
    }

    public function scopeDefault($query)
    {
        return $query->where('name', 'default');
    }
}
