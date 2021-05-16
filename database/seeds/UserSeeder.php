<?php

use App\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(User::class, 100)->create();
        for ($i = 1; $i <= 10; $i++) {
            factory(User::class, 1)->create([
                'email' => "foo{$i}@bar.baz"
            ]);
        }
    }
}
