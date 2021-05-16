<?php

/** @var Factory $factory */

use App\Content;
use App\Image;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(Content::class, static function (Faker $faker) {
    $type = $faker->boolean(20) ? 'page' : 'post';
    $result = [
        'type' => $type,
        'slug' => $faker->slug,
        "title" => $faker->sentence(5),
        "description" => $faker->sentence(10),
        "body" => "<p>" . $faker->realText($faker->numberBetween(200, 400)) . "</p>",
        "og_title" => $faker->sentence(5),
        "og_description" => $faker->sentence(10),
        'views' => $faker->numberBetween(0, 100),
    ];

    if ($result['type'] === 'post') {
        $result['taxonomy'] = $faker->randomElement(["Healthy lifestyle", "Training", "Healthy meal"]);
    } else {
        $result['sitemaps_weight'] = 0.1;
    }

    return $result;
});

$factory->afterCreating(Content::class, static function ($post) {
    factory(Image::class, 1)
        ->state('post')
        ->create([
            'imageable_id' => $post->id,
        ]);
});
