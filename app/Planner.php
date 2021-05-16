<?php

namespace App;

use App\Helpers\PlannerStaticHelper;
use App\Relationships\HasMany\Extras;
use Illuminate\Database\Eloquent\Model;
use App\Relationships\BelongsTo\User;
use App\Relationships\HasMany\Meals;

class Planner extends Model
{
    use User;
    use Meals;
    use Extras;

    /**
     * Mass assignment.
     *
     * @var array
     */
    protected $fillable = [
        'starts', 'ends', 'goal', 'calories_goal', 'weight', 'validation_data', 'shopping_list_data', 'need_to_update'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'starts' => 'date',
        'ends' => 'date',
        'goal' => 'float',
        'validation_data' => 'collection',
        'shopping_list_data' => 'collection',
    ];

    /**
     * Return shopping list data
     *
     * @return mixed
     */

    public function shoppingList()
    {
        return $this['shopping_list_data'];
    }

    /**
     * Calculate new shopping list
     *
     * @return array
     */

    public function calcShoppingList()
    {
        return PlannerStaticHelper::calculateStoreList($this);
    }


}
