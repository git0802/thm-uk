<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use Doctrine\DBAL\Types\FloatType;
use Doctrine\DBAL\Types\Type;

class ModifyHeightFloat extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Type::hasType('double')) {
            Type::addType('double', FloatType::class);
        }

        Schema::table('users', function (Blueprint $table) {
            $table->unsignedDouble('height', 8, 2)->nullable()->change();
            $table->unsignedDouble('weight', 8, 3)->nullable()->change();
        });

        Schema::table('planners', function (Blueprint $table) {
            $table->unsignedDouble('weight', 8, 3)->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (!Type::hasType('double')) {
            Type::addType('double', FloatType::class);
        }

        Schema::table('users', function (Blueprint $table) {
            $table->unsignedSmallInteger('height')->nullable()->change();
            $table->unsignedDouble('weight', 4, 1)->change();
        });

        Schema::table('planners', function (Blueprint $table) {
            $table->unsignedInteger('weight')->default(0)->change();
        });
    }
}
