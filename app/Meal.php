<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Relationships\BelongsTo\Product;
use App\Relationships\BelongsTo\Planner;
use Illuminate\Support\Facades\Auth;

class Meal extends Model
{

    // todo: clear this
//    use Product, Planner;
    use Planner;

    /**
     * Mass assignment.
     *
     * @var array
     */
    protected $fillable = [
        'eating', 'is_eaten', 'servings', 'date', 'product_id', 'product_data', 'planner_id', 'from_preset_id'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'date' => 'date:Y-m-d',
        'is_eaten' => 'boolean',
        'product_data' => 'collection'
    ];

    protected $hidden = [
        'product_data'
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $settings = Setting::where('name', 'guest')->first();
        if ($settings) {
            $guestSettings = $settings->settings_json;

            if ($guestSettings['enabled'] && Auth::guard('sanctum')->check() && Auth::guard('sanctum')->user()->is_guest) {
                $this->setTable('guest_'.$this->getTable());
            }
        }
    }

    public function product()
    {
        return $this->product_data;
    }

    public function store()
    {
        return $this->product_data['store'];
    }

    public function dish()
    {
        return $this->product_data['dish'];
    }
}


