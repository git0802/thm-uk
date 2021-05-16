<?php

namespace App\Relationships\BelongsTo;

trait Owner
{
    /**
     * Get the user.
     */
    public function owner()
    {
        return $this->belongsTo('App\User', 'owner_id');
    }
}
