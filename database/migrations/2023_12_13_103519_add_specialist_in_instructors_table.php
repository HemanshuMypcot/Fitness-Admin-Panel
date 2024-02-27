<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSpecialistInInstructorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('instructors', function (Blueprint $table) {
            $table->integer('specialist_in')->unsigned()->after('status')->nullable();
        });
        Schema::table('instructor_translations', function (Blueprint $table) {
            $table->dropColumn('specialist_in');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('instructors', function (Blueprint $table) {
            $table->dropColumn('specialist_in');
        });
        Schema::table('instructor_translations', function (Blueprint $table) {
            $table->string('specialist_in')->after('name')->nullable();
        });
    }
}
