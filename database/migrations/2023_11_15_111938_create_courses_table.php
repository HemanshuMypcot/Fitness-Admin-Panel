<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('course_category_id')->unsigned();
            $table->integer('instructor_id')->unsigned();
            $table->date('date_start');
            $table->date('date_end');
            $table->time('time_start');
            $table->time('time_end');
            $table->integer('capacity');
            $table->integer('sequence');
            $table->string('amount');
            $table->string('tax');
            $table->string('service_charge');
            $table->string('total');
            $table->longText('opens_at');
            $table->foreign('instructor_id')->references('id')->on('instructors')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('course_category_id')->references('id')->on('course_categories')->onDelete('cascade')->onUpdate('cascade');
            $table->dateTime('registration_start');
            $table->dateTime('registration_end');
            $table->enum('type', ['one_day', 'recurring'])->default('one_day');
            $table->enum('status', ['1', '0'])->default('1');
            $table->enum('registration_allowed', ['Y', 'N'])->default('Y');
            $table->enum('cancellation_allowed', ['Y', 'N'])->default('Y');
            $table->enum('visible_in_app', ['Y', 'N'])->defaut('Y');
            $table->integer('created_by')->default(0);
            $table->integer('updated_by')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('courses');
    }
}
