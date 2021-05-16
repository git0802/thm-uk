<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrderToContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('contents', static function (Blueprint $table) {
            $table->unsignedInteger("order")->nullable();
            $table->boolean("is_featured")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('contents', static function (Blueprint $table) {
            $table->dropColumn([
                'order', 'is_featured',
            ]);
        });
    }
}
