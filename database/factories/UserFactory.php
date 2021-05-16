<?php

/** @var Factory $factory */

use App\CouponCode;
use App\CreditCard;
use App\Image;
use App\Store;
use App\User;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Support\Str;

$factory->define(User::class, static function (Faker $faker) {
    $gender = $faker->boolean(60) ? 'female' : 'male';
    return [
        'name' => $faker->firstName($gender),
        'last_name' => $faker->lastName,
        'phone' => $faker->regexify('\d{11}'),
        'email' => $faker->unique()->safeEmail,
        'activated_at' => $faker->boolean(50) ? now() : null,
        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        'age' => $faker->numberBetween(18, 120),
        'gender' => $gender,
        'height' => $faker->numberBetween(145, 250),
        'weight' => $faker->numberBetween(50, 175),
        'is_spam_wanted' => $faker->boolean(25),
        'activation_token' => Str::random(50),
        'initial_goal' => $faker->randomElement([-2, -1.5, -1, -0.5, -0.25, 0, 0.25, 0.5, 1, 1.5, 2]),
        'initial_calories_goal' => $faker->numberBetween(1000, 3500),
    ];
});

$factory->afterCreating(User::class, static function ($user, $faker) {
    if ($faker->boolean(50)) {
        if ($faker->boolean(60)) {
            factory(Image::class, 1)
                ->state('user')
                ->create([
                    'imageable_id' => $user->id,
                ]);
        }

        if ($faker->boolean(10)) {
            factory(Store::class, $faker->numberBetween(1,3))
                ->create([
                    'owner_id' => $user->id,
                ]);
        }

        if ($faker->boolean(30)) {
            $code = CouponCode::all()->random();
            if (!$code->user) {
                $code->user_id = $user->id;
                $code->used_at = now();
                $code->save();
            }
        }

        $user->activated_at = now();
        $user->finished_setup = true;
        $user->save();
    }
});
