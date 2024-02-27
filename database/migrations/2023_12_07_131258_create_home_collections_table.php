<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHomeCollectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('home_collections', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('type', ['Single', 'Course'])->default('Single');
            $table->integer('sequence')->nullable();
            $table->enum('is_scrollable', [0, 1])->default(0);
            $table->integer('display_in_column')->default(1);
            $table->enum('status', [0, 1])->default(1);
            $table->integer('created_by')->default(0);
            $table->integer('updated_by')->default(0);
            $table->softDeletes();
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
        Schema::dropIfExists('home_collections');
    }
}
