<?php

namespace App\Relationships\MorphOne;

trait Image
{
    /**
     * Get the post's image.
     */
    public function image()
    {
        return $this->morphOne('App\Image', 'imageable');
    }
}
