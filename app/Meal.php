<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Relationships\BelongsTo\Product;
use App\Relationships\BelongsTo\Planner;

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


