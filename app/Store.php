<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Relationships\BelongsTo\Owner;
use App\Relationships\HasMany\Products;
use App\Helpers\OwnerHelpers;
use App\Relationships\MorphOne\Image;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Store model
 *
 * @package App
 */
class Store extends Model
{
    use Owner, Image, OwnerHelpers, Products, SoftDeletes;

    /**
     * Mass assignment.
     *
     * @var array
     */
    protected $fillable = [ 'name' ];
}
