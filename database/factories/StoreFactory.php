<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Image;
use App\Product;
use App\Store;
use Faker\Generator as Faker;

$factory->define(Store::class, function (Faker $faker) {
    return [
        'name' => str_replace(".", "", $faker->text($faker->numberBetween(5, 128))),
        'owner_id' => null,
    ];
});

$factory->afterCreating(Store::class, function ($store, $faker) {
    if ($store->owner_id === null || ($store->owner_id !== null && $faker->boolean(90))) {
        factory(Image::class, 1)
            ->state('store')
            ->create([
                'imageable_id' => $store->id,
            ]);
    }

    factory(Product::class, $faker->numberBetween(5, 20))->create([
        'store_id' => $store->id,
        'owner_id' => $store->owner_id,
    ]);
});
