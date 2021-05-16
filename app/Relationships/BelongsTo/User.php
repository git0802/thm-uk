<?php

namespace App\Relationships\BelongsTo;

trait User
{
    /**
     * Get the user.
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
