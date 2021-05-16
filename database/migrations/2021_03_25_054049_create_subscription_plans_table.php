<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionPlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscription_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('title');
            $table->string('stripe_product_id')->default("");
            $table->string('stripe_price_id')->default("");
            $table->unsignedDouble('price', 5, 2)->nullable();
            $table->unsignedDouble('months', 5, 2)->nullable();
            $table->unsignedDouble('sale', 5, 2)->nullable();
            $table->unsignedDouble('amount', 5, 2)->nullable();
            $table->unsignedDouble('price_sale', 5, 2)->nullable();
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
        Schema::dropIfExists('subscription_plans');
    }
}
