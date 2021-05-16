<?php

namespace App\Relationships\HasMany;

use App\Product;

trait Products
{
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
