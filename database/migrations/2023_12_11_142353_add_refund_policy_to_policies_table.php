<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRefundPolicyToPoliciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('policies', function (Blueprint $table) {
            DB::statement("ALTER TABLE policies MODIFY COLUMN type ENUM('about', 'terms', 'policy', 'cancellation_policy', 'refund_policy')");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('policies', function (Blueprint $table) {
            DB::statement("ALTER TABLE policies MODIFY COLUMN type ENUM('about', 'terms', 'policy','cancellation_policy')");
        });
    }
}
