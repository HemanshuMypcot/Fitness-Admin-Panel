<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('admin_name', 150);
            $table->string('nick_name', 100);
            $table->string('email', 100)->unique();
            $table->integer('country_id')->comment('phone_code')->nullable();
            $table->string('phone', 15);
            $table->string('password')->nullable();
            $table->text('address')->nullable();
            $table->integer('role_id')->unsigned();
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade')->onUpdate('cascade');
            $table->enum('is_head', [1, 0])->default(0);
            $table->enum('on_leave', [1, 0])->default(0);
            $table->enum('login_allowed', [1, 0])->default(1);
            $table->enum('status', [1, 0])->default(1);
            $table->enum('sms_notification', [1, 0])->default(1);
            $table->enum('email_notification', [1, 0])->default(1);
            $table->enum('whatsapp_notification', [1, 0])->default(1);
            $table->enum('force_pwd_change_flag', [1, 0])->default(1);
            $table->date('pwd_expiry_date')->nullable();
            $table->integer('created_by')->default(0);
            $table->integer('updated_by')->default(0);
            $table->softDeletes();
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
        Schema::dropIfExists('admins');
    }
}
