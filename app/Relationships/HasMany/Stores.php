<?php

namespace App\Relationships\HasMany;

use App\Store;

trait Stores
{
    public function stores()
    {
        return $this->hasMany(Store::class, 'owner_id');
    }
}
