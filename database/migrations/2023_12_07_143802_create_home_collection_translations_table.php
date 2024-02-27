<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHomeCollectionTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('home_collection_translations', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('home_collection_id')->unsigned();
            $table->foreign('home_collection_id')->references('id')->on('home_collections')->onDelete('cascade')->onUpdate('cascade');
            $table->string('locale')->nullable();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->unique(['home_collection_id','locale']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('home_collection_translations');
    }
}
