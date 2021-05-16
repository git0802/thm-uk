<?php

namespace App\Relationships\BelongsTo;

trait Planner
{
    public function planner()
    {
        return $this->belongsTo(\App\Planner::class);
    }
}
