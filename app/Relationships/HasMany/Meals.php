<?php

namespace App\Relationships\HasMany;

use App\Meal;

trait Meals
{
    public function meals()
    {
        return $this->hasMany(Meal::class);
    }
}
