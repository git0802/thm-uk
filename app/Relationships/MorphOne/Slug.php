<?php

namespace App\Relationships\MorphOne;

trait Slug
{
    /**
     * Get the post's image.
     */
    public function slug()
    {
        return $this->morphOne('App\Slug', 'slugable');
    }
}
