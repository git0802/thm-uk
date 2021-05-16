<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Relationships\MorphOne\Image;

class Video extends Model
{
    use Image;

    protected $fillable = [
        'title', 'description', 'url', 'youtube_id'
    ];
}
