<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeDatatypeInCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->decimal('amount', 12, 2)->change();
            $table->decimal('tax', 12, 2)->change();
            $table->decimal('service_charge', 12, 2)->change();
            $table->decimal('total', 12, 2)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->string('amount')->change();
            $table->string('tax')->change();
            $table->string('service_charge')->change();
            $table->string('total')->change();
        });
    }
}
