<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\SiteContent;
use Faker\Generator as Faker;

$factory->define(SiteContent::class, function (Faker $faker) {
    return [
        'block_name' => $faker->realText(10),
        'title' => $faker->realText(20),
        'description' => $faker->realText(180),
    ];
});
