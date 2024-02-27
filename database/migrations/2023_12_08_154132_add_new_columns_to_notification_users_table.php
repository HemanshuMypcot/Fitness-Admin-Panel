<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewColumnsToNotificationUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('notification_users', function (Blueprint $table) {
            $table->integer('user_device_id')->nullable();
            $table->enum('is_send',['1','0'])->default(0)->nullable();
            $table->text('response')->nullable();
            $table->dateTime('trigger_date')->nullable();
            $table->integer('attempt')->default(0)->nullable();
            $table->string('title')->nullable();
            $table->longText('body')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('notification_users', function (Blueprint $table) {
            $table->dropColumn(['is_send','user_device_id','response','trigger_date','attempt','title','body']);
        });
    }
}
