<?php

namespace App;

use App\Relationships\HasMany\Stores;
use Illuminate\Database\Eloquent\Builder;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Relationships\HasMany\Codes;
use App\Relationships\MorphOne\Image;
use App\Relationships\HasMany\Planners;

class User extends Authenticatable
{
    use HasApiTokens;
    use Notifiable;
    use Image;
    use Planners;
    use Codes;
    use Stores;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'last_name',
        'email',
        'password',
        'age',
        'height',
        'weight',
        'gender',
        'phone',
        'is_spam_wanted',
        'initial_goal',
        'initial_calories_goal',
        'goal'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'is_admin',
        'activated_at',
        'activation_code',
        'created_at',
        'updated_at',
        'id',
        'is_spam_wanted',
        'reset_password_token'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'activated_at' => 'datetime'
    ];

    /**
     * Find user by email.
     *
     * @param Builder $query
     * @param string $email
     * @return Builder
     */
    public function scopeEmail($query, $email): Builder
    {
        return $query->where('email', $email);
    }

    public function subscription()
    {
        return $this->hasOne('App\Subscription');
    }

    public function coupon()
    {
        return $this->hasOne('App\CouponCode');
    }

    public function checkPaid()
    {
        if($this->is_admin) {
            return true;
        } else {
            return (bool) $this->subscription->paid;
        }
    }


    public function getPaidStrings()
    {
        $array = [
            0 => 'Not Paid',
            1 => 'Trial',
            2 => 'Paid',
        ];

        if (array_key_exists($this->subscription->paid, $array)) {
            return $array[$this->subscription->paid];
        }
    }
}
