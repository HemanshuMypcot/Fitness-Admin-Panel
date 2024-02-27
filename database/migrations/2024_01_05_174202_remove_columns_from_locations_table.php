<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveColumnsFromLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('locations', function (Blueprint $table) {
            $table->dropForeign(['state_id']);
        });

        Schema::table('locations', function (Blueprint $table) {
            $table->dropColumn(['type', 'phone', 'email', 'image', 'working_days', 'location', 'sequence']);
        });
    }

    public function down()
    {
        Schema::table('locations', function (Blueprint $table) {
            $table->string('type')->default('ashram');
            $table->longText('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('image');
            $table->text('working_days')->nullable();
            $table->string('location');
            $table->integer('sequence');
            $table->integer('state_id')->unsigned()->nullable();
            $table->foreign('state_id')->references('id')->on('states')->onDelete('cascade')->onUpdate('cascade');
        });
    }
}
