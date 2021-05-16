<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        if (!App::environment('production')) {
            // Remove previous images
            Storage::deleteDirectory('images');
            // Fake values
            $this->call(ContentSeeder::class);
            $this->call(CouponSetSeeder::class);
            $this->call(UserSeeder::class);
            $this->call(StoreSeeder::class);
            $this->call(DishSeeder::class);
            $this->call(PlannerSeeder::class);
            $this->call(QuoteSeeder::class);
            $this->call(VideoSeeder::class);
        }
    }
}
