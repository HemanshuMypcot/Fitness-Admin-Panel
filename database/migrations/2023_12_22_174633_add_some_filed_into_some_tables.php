<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSomeFiledIntoSomeTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('instructors',function (Blueprint $table){
            $table->string('nick_name')->nullable()->after('specialist_in');
        });
        Schema::table('course_categories',function (Blueprint $table){
            $table->string('nick_name')->nullable()->after('id');
        });
        Schema::table('courses',function (Blueprint $table){
            $table->string('sku_code')->nullable()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('instructors',function (Blueprint $table){
            $table->dropColumn('nick_name');
        });
        Schema::table('course_categories',function (Blueprint $table){
            $table->dropColumn('nick_name');
        });
        Schema::table('courses',function (Blueprint $table){
            $table->dropColumn('sku_code');
        });
    }
}
