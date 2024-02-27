<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMovieCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movie_categories', function (Blueprint $table) {
            $table->id();
            $table->string('category');
            $table->string('name');
            $table->enum('status', [1, 0])->default(1);
            $table->string('season')->nullable();
            $table->string('yt_link')->nullable();
            $table->string('genre');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('movie_categories');
    }
}
