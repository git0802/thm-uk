<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGuestMealsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guest_meals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('planner_id');
            $table->enum('eating', ['breakfast', 'lunch', 'dinner', 'snacks'])
                ->default("breakfast");
            $table->boolean("is_eaten")
                ->default(false);
            $table->unsignedInteger("servings")
                ->default(1);
            $table->integer('from_preset_id')->nullable();
            $table->timestamp('date');
            $table->json('product_data')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('guest_meals');
    }
}
