<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveColumnsFromLocationTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('location_translations', function (Blueprint $table) {
            $table->dropColumn(['title', 'description', 'do', 'dont']);
        });
    }

    public function down()
    {
        Schema::table('location_translations', function (Blueprint $table) {
            $table->string('title')->nullable();
            $table->string('description')->nullable();
            $table->text('do')->nullable();
            $table->text('dont')->nullable();
        });
    }
}
