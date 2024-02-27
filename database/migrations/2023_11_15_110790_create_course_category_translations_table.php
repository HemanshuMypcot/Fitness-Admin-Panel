<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourseCategoryTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_category_translations', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('course_category_id')->unsigned();
            $table->string('category_name');
            $table->string('locale')->index();
            $table->foreign('course_category_id')->references('id')->on('course_categories')->onDelete('cascade')->onUpdate('cascade');
            $table->unique(['course_category_id','locale']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('course_category_translations');
    }
}
