<?php

namespace App\Relationships\BelongsTo;

trait Product
{
    public function product()
    {
        return $this->belongsTo(\App\Product::class);
    }
}
