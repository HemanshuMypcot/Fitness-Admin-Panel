<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangePaymentStatusFiledInBookedCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `booked_courses` CHANGE `payment_status` `payment_status` ENUM('pending','failed','paid','free')   NOT NULL DEFAULT 'pending';");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE `booked_courses` CHANGE `payment_status` `payment_status` ENUM('pending','failed','paid')   NOT NULL DEFAULT 'pending';");
    }
}
