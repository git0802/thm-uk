<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Extra extends Model
{
    public $timestamps = false;

    protected $dates = ['date'];

    protected $casts = [
        'date' => 'date'
    ];

    protected $fillable = [
        'date', 'value'
    ];
}
