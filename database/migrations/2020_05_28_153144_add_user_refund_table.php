<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserRefundTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_refunds', function (Blueprint $table) {
            $table->integer('type')->default(1)->comment('退款类型：1-普通退款 2-赔付退款')->change();
            $table->integer('refund_way')->nullable()->comment('退款方式：1-原路退还 2-转账退款');
            $table->integer('refund_channels')->nullable()->comment('退款渠道：1-微信 2-支付宝');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
