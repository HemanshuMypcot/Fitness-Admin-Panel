<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocationsTable extends Migration
{
    public function up()
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type')->default('ashram');
            $table->longText('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('image');
            $table->text('working_days')->nullable();
            $table->string('location');
            $table->string('latitude');
            $table->string('longitude');
            $table->string('google_address');
            $table->integer('sequence');
            $table->integer('state_id')->unsigned()->nullable();
            $table->foreign('state_id')->references('id')->on('states')->onDelete('cascade')->onUpdate('cascade');
            $table->enum('visible_on_app', [0, 1])->default(1);
            $table->enum('status', [0, 1])->default(1);
            $table->integer('created_by')->default(0);
            $table->integer('updated_by')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('locations');
    }
}
