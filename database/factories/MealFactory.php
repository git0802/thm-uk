<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Meal;
use Faker\Generator as Faker;

$factory->define(Meal::class, function (Faker $faker) {
    return [
        'product_id' => 0,
        'planner_id' => 0,
        'eating' => $faker->randomElement(['breakfast', 'lunch', 'dinner', 'snacks']),
        'is_eaten' => $faker->boolean(30),
        'servings' => $faker->numberBetween(1, 5),
        'date' => now(),
    ];
});
