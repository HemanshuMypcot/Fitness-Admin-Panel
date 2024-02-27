<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MigrationToAddTwoColumnInBookedCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('booked_courses', function (Blueprint $table) {
            $table->enum('payment_mode', ['COD', 'Online'])->default('COD');
            $table->enum('payment_status', ['pending', 'payed'])->default('pending');
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
            $table->dropColumn('payment_mode');
            $table->dropColumn('payment_status');
        });
    }
}
