<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UserOrdersAddRenewalNotice extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_orders', function (Blueprint $table) {
            $table->boolean('renewal_notice')->default(0)->comment('续费提醒，0未取消，1取消 订单结束或手动取消');

            $table->timestamp('cancel_renewal_notice')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_orders', function (Blueprint $table) {
            $table->dropColumn('renewal_notice', 'cancel_renewal_notice');
        });
    }
}
