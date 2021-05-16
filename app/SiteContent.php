<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SiteContent extends Model
{
    protected $fillable = [
        'block_name', 'title', 'description'
    ];
}
