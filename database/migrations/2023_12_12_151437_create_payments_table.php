<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('transaction_code')->nullable();
            $table->integer('user_id')->unsigned();
            $table->integer('booked_course_id')->unsigned();
            $table->enum('payment_mode', ['COD', 'Online'])->default('COD');
            $table->enum('payment_status', ['pending', 'failed', 'payed'])->default('pending');
            $table->integer('amount')->nullable();
            $table->datetime('transaction_date')->nullable();
            $table->longtext('remark')->nullable();
            $table->foreign('booked_course_id')->references('id')->on('booked_courses')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('payments');
    }
}
