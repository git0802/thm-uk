<?php

namespace App\Helpers;

use Illuminate\Database\Eloquent\Builder;

trait OwnerHelpers
{
    /**
     * Get products for all users.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeDefault($query)
    {
        return $query->where("owner_id", null);
    }

    /**
     * Get products for selected user with default products.
     *
     * @param Builder $query
     * @param integer $owner_id
     * @return Builder
     */
    public function scopeByOwnerId($query, $owner_id, $my_dish = null)
    {
        return $query
            ->default()
            ->orWhere("owner_id", $owner_id)
            ->when($my_dish, function ($query, $my_dish) {
                return $query->whereNotIn('id', [$my_dish]);
            });
    }

    /**
     * Get products for selected user without default products.
     *
     * @param Builder $query
     * @param integer $owner_id
     * @return Builder
     */
    public function scopeForOwner($query, $owner_id)
    {
        return $query->where("owner_id", $owner_id);
    }
}
