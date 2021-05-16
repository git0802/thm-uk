<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', static function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->unsignedBigInteger("store_id");
            $table->unsignedBigInteger("owner_id")->nullable();
            $table->string("serving_size", 255)->default("1");
            $table->string("package_size", 255)->nullable();
            $table->unsignedBigInteger("calories")->default(0);
            $table->unsignedFloat("cost", 8 ,2)->default(0);
            $table->boolean("is_dish")->default(false);
            $table->string("original_url")->nullable();
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
        Schema::dropIfExists('products');
    }
}
