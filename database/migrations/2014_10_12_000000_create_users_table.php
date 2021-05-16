<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->unsignedDouble('weight',4, 1);
            $table->unsignedSmallInteger('height');
            $table->unsignedTinyInteger('age');
            $table->enum('gender', ['male', 'female']);
            $table->string('phone');
            $table->string('password');
            $table->boolean('is_admin')->default(false);
            $table->boolean('is_spam_wanted')->default(true);
            $table->timestamp('activated_at')->nullable();
            $table->string('activation_token', 50)->nullable();
            $table->string('reset_password_token', 60)->nullable();
            $table->double('initial_goal', 2, 1)->default(0);
            $table->unsignedInteger('initial_calories_goal')->default(1200);
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
        Schema::dropIfExists('users');
    }
}
