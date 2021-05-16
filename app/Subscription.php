<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'payment_method',
        'start',
        'ending',
        'customer_id',
        'subscription_plan_id',
        'subscription_id',
        'paid'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'id',
        'user_id',
        'created_at',
        'updated_at',
        'customer_id'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'start'      => 'datetime',
        'ending'     => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function subscriptionPlan()
    {
        return $this->belongsTo('App\SubscriptionPlan', 'subscription_plan_id');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
