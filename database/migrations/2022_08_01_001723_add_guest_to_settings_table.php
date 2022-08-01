<?php

use App\Setting;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGuestToSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Setting::where('name', 'guest')->exists()) {
            Setting::create([
                'name' => 'guest',
                'value' => 'empty',
                'settings_json'=> [
                    'enabled' => false,
                    'user' => [
                        'name' => 'Guest',
                        'weight' => 321,
                        'height' => 190,
                        'age' => 21,
                        'gender' => 'male',
                        'initial_calories_goal' => 3052,
                    ]
                ]
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
