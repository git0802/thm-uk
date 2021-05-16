<?php

use Illuminate\Database\Seeder;
use Faker\Generator as Faker;

class PlannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @param Faker $faker
     * @return void
     */
    public function run(Faker $faker)
    {
        $users = \App\User::where('activated_at', "!=", null)->where('is_admin', 0)->get();

        foreach ($users as $user) {
            if ($faker->boolean(75)) {
                factory(\App\Planner::class, 1)->create([
                    'user_id' => $user->id
                ]);
                factory(\App\Planner::class, 1)->create([
                    'user_id' => $user->id,
                    'starts' => now()->startOfWeek()->subWeek()->format('Y-m-d H:i:s'),
                    'ends' => now()->endOfWeek()->subWeek()->format('Y-m-d H:i:s'),
                ]);
            }
        }
    }
}
