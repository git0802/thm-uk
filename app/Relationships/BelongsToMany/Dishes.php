<?php

namespace App\Relationships\BelongsToMany;

use App\Product;

trait Dishes
{
    public function dish()
    {
        return $this->belongsToMany(Product::class, 'dish_product', 'dish_id', 'product_id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'dish_product', 'dish_id', 'product_id');
    }
}
