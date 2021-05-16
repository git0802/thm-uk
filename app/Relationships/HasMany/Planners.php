<?php


namespace App\Relationships\HasMany;


trait Planners
{
    public function planners()
    {
        return $this->hasMany('App\Planner');
    }
}
