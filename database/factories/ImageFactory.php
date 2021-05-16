<?php

use App\Content;
use App\Video;
use App\Store;
use App\Product;
use App\User;

/** @var Factory $factory */

use App\Image;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;

$factory->define(Image::class, static function (Faker $faker) {
    return [
        'imageable_type' => '',
        'imageable_id' => 0,
        'path' => ''
    ];
});

$factory->state(Image::class, 'user', static function () {
    return [ 'imageable_type' => User::class ];
});

$factory->state(Image::class, 'product', static function () {
    return [ 'imageable_type' => Product::class ];
});

$factory->state(Image::class, 'store', static function () {
    return [ 'imageable_type' => Store::class ];
});

$factory->state(Image::class, 'video', static function () {
    return [ 'imageable_type' => Video::class ];
});

$factory->state(Image::class, 'post', static function () {
    return [ 'imageable_type' => Content::class ];
});

$factory->afterCreating(Image::class, static function ($image, $faker) {
    $path = 'images/';
    $category = 'abstract';

    switch ($image->imageable_type) {
        case User::class:
            $path .= 'avatars';
            $category = 'people';
            break;
        case Product::class:
            $path .= 'products';
            $category = 'food';
            break;
        case Store::class:
            $path .= 'stores';
            $category = 'business';
            break;
        case Video::class:
            $path .= 'videos';
            $category = 'video';
            break;
        case Content::class:
            $path .= 'posts';
            $category = 'post';
            break;
        default:
            break;
    }

    // To use lorempixel.com: $img = $faker->image('/tmp', 640, 480, $category)
    $img = new File(storage_path('app/seeder-images/' . $category . '-' . $faker->numberBetween(1, 3) . '.jpg'));
    $location = Storage::putFile($path, $img);
    $image->path = $location;
    $image->save();
});
