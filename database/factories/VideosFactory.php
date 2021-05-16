<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Image;
use App\Video;
use Faker\Generator as Faker;

$factory->define(Video::class, function (Faker $faker) {
    return [
        'title' => $faker->unique()->sentence($faker->numberBetween(4, 7)),
        'description' => $faker->unique()->sentence($faker->numberBetween(7, 10)),
        'url' => 'https://www.youtube.com/watch?v=UBMk30rjy0o',
        'youtube_id' => 'UBMk30rjy0o',
    ];
});

$factory->afterCreating(Video::class, function ($video) {
    factory(Image::class, 1)
        ->state('video')
        ->create([
            'imageable_id' => $video->id,
        ]);
});
