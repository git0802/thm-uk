<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Quote extends Model
{
    public $timestamps = false;

    protected $fillable = ['quote'];
}
