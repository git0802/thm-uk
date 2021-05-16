<?php

namespace App;

use App\Helpers\MealHelper;
use Illuminate\Database\Eloquent\Model;
use App\Relationships\BelongsTo\Owner;
use App\Helpers\OwnerHelpers;
use App\Relationships\BelongsToMany\Dishes;
use App\Relationships\BelongsTo\Store;
use App\Relationships\MorphOne\Image;

/**
 * Product model
 *
 * @package App
 */
class Product extends Model
{
    use Store, Image, Owner, Dishes, OwnerHelpers, MealHelper;

    /**
     * Mass assignment.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'store_id',
        'serving_size',
        'calories',
        'cost',
        'is_dish',
        'original_url',
        'package_size',
        'original_url',
        'product_data',
        'owner_id',
    ];

//    protected $hidden = [
//        'original_url',
//    ];

    protected $casts = [
        'cost' => 'float',
        'calories' => 'integer'
    ];

    public static function replaceProductAtPlanner(int $parentId, Product $childProduct, Planner $planner, bool $isEdit): void
    {
        $meals = $planner->meals()->where('product_id', $parentId)->get();
        foreach ($meals as $meal) {
            $meal->product_data = (new Product)->productDataArray($childProduct)->toArray();
            if(!$isEdit) {
                $meal->product_id = $childProduct->id;
            }
            $meal->save();
        }

    }
}
