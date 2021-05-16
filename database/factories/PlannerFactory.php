<?php

/** @var Factory $factory */

use App\Extra;
use App\Meal;
use App\Planner;
use App\Product;
use Illuminate\Database\Eloquent\Factory;
use Faker\Generator as Faker;

$factory->define(Planner::class, static function (Faker $faker) {
    return [
        'user_id' => 0,
        'starts' => now()->startOfWeek()->format('Y-m-d H:i:s'),
        'ends' => now()->endOfWeek()->format('Y-m-d H:i:s'),
        'goal' => $faker->randomElement([-2, -1.5, -1, -0.5, -0.25, 0, 0.25, 0.5, 1, 1.5, 2]),
        'calories_goal' => $faker->numberBetween(1000, 3500),
        'extra_calories' => $faker->numberBetween(-1000, 1000),
        'weight' => $faker->numberBetween(95, 105),
        'finished_setup' => $faker->boolean(25),
    ];
});

$factory->afterCreating(Planner::class, static function ($planner, $faker) {
    $product_ids = Product::default()->pluck('id');
    for ($i = 0; $i < 7; $i++) {
        factory(Meal::class, $faker->numberBetween(1, 5))->create([
            'product_id' => $product_ids->random(),
            'planner_id' => $planner->id,
            'date' => $planner->starts->addDays($i),
        ]);
        if ($faker->boolean()) {
            $extraCount = $faker->numberBetween(1, 3);
            for ($n = 0; $n < $extraCount; $n++) {
                Extra::create([
                    'planner_id' => $planner->id,
                    'value' => $faker->numberBetween(-200, 200),
                    'date' => $planner->starts->addDays($i),
                ]);
            }
        }
    }
});
