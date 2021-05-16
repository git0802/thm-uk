<?php

use App\Store;
use Illuminate\Database\Seeder;
use Faker\Generator as Faker;

class DishSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @param Faker $faker
     * @return void
     */
    public function run(Faker $faker)
    {
        foreach (Store::where("owner_id", "!=", null)->get() as $store) {
            foreach ($store->products as $product) {
                if ($faker->boolean(20)) {
                    $product->is_dish = true;
                    for ($i = 0; $i < $faker->numberBetween(1, 5); $i++) {
                        $product->dish()->create([
                            'name' => str_replace(".", "", $faker->text($faker->numberBetween(5, 128))),
                            'store_id' => $product->store_id,
                            'owner_id' => $product->owner_id,
                            'serving_size' => $faker->numberBetween(1, 100) * 10,
                            'calories' => $faker->numberBetween(10, 500),
                            'cost' => $faker->randomFloat(2, 0.29, 500),
                        ]);
                    }
                    $product->save();
                }
            }
        }
    }
}
