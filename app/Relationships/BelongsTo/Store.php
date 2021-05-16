<?php

namespace App\Relationships\BelongsTo;

trait Store
{
    public function store()
    {
        return $this->belongsTo('App\Store');
    }
}
