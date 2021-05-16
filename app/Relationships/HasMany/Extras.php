<?php

namespace App\Relationships\HasMany;

use App\Extra;

trait Extras
{
    public function extras()
    {
        return $this->hasMany(Extra::class);
    }
}
