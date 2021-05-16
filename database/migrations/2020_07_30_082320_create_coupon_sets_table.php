<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouponSetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('coupon_sets', static function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('title');
            $table->enum('type', ['percent', 'month', 'fixed'])->default('fixed');
            $table->string('stripe_product_id')->default("");
            $table->string('stripe_price_id')->default("");
            $table->unsignedDouble('cost', 5, 2)->nullable();
            $table->unsignedDouble('value', 5, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('coupon_sets');
    }
}
