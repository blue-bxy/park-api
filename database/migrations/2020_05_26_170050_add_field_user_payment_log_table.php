<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldUserPaymentLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_payment_logs', function (Blueprint $table) {
            $table->unsignedTinyInteger('pay_type')->default(1)->comment('支付类型:1-余额抵扣，2-第三方抵扣，3-积分抵扣');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_payment_logs', function (Blueprint $table) {
            $table->dropColumn('pay_type');
        });
    }
}
