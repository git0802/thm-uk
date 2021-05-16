<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Relationships\BelongsTo\User as BelongsToUser;

class CouponCode extends Model
{
    use BelongsToUser;

    protected $fillable = [
        'code', 'used_at', 'coupon_set_id'
    ];

    protected $casts = [
        'used_at' => 'datetime'
    ];

    public $timestamps = false;

    public function scopeUsed($query)
    {
        return $query->where('user_id', '!=', null);
    }

    public function scopeFresh($query)
    {
        return $query->where('user_id', null);
    }

    public function set()
    {
        return $this->belongsTo('App\CouponSet', 'coupon_set_id');
    }
}
