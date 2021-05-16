<?php

/** @var Factory $factory */

use App\Product;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(Product::class, static function (Faker $faker) {
    return [
        'name' => str_replace(".", "", $faker->text($faker->numberBetween(5, 128))),
        'store_id' => 0,
        'owner_id' => null,
        'serving_size' => $faker->numberBetween(1, 100) * 10,
        'calories' => $faker->numberBetween(10, 500),
        'cost' => $faker->randomFloat(2, 0.29, 500),
        'is_dish' => false,
    ];
});

$factory->afterCreating(Product::class, static function ($product, $faker) {
    if ($product->owner_id === null || ($product->owner_id !== null && $faker->boolean(50) && !$product->is_dish)) {
        factory(\App\Image::class, 1)
            ->state('product')
            ->create([
                'imageable_id' => $product->id,
            ]);
    }
});
