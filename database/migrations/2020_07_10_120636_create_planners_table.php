<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlannersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('planners', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->double('goal', 2, 1)->default(0);
            $table->unsignedInteger('calories_goal')->default(1200);
            $table->unsignedInteger('weight')->default(0);
            $table->integer('extra_calories')->default(0);
            $table->timestamp('starts')->nullable();
            $table->timestamp('ends')->nullable();
            $table->boolean('finished_setup')->default(false);
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
        Schema::dropIfExists('planners');
    }
}
