<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('contents', static function (Blueprint $table) {
            $table->id();
            $table->enum("type", [ "page", "post" ])->default('post');
            $table->string("slug")->unique();
            $table->string("title");
            $table->longText("body");
            $table->string("description")->default("");
            $table->string("og_title")->default("");
            $table->unsignedDouble("sitemaps_weight", 2, 1)->default(0.5);
            $table->string("og_description")->default("");
            $table->enum("taxonomy", [
                "Healthy lifestyle", "Training", "Healthy meal"
            ])->nullable();
            $table->unsignedBigInteger("views")->default(0);
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
        Schema::dropIfExists('contents');
    }
}
