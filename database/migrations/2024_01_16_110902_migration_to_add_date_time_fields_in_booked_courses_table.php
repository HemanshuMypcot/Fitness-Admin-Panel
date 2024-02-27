<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MigrationToAddDateTimeFieldsInBookedCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('booked_courses', function (Blueprint $table) {
            $table->date('course_start_date')->nullable()->after('details');
            $table->date('course_end_date')->nullable()->after('course_start_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('booked_courses', function (Blueprint $table) {
            $table->dropColumn('course_start_date');
            $table->dropColumn('course_end_date');
        });
    }
}
