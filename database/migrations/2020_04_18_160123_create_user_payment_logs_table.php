<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserPaymentLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_payment_logs', function (Blueprint $table) {
            $table->id('id')->comment('主键ID');
			
			$table->unsignedBigInteger('user_id')->comment('用户ID');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
			$table->foreignId('order_no')->comment('订单编号');
			$table->string('trade_no')->nullable()->comment('支付平台交易号');
			$table->string('buyer_account')->nullable()->comment('支付平台用户账号');
			$table->string('arrival_account')->nullable()->comment('用户到账账号');
			$table->integer('money_amount')->default(0)->comment('金额');
			$table->string('request_info')->nullable()->comment('请求原始信息');
			$table->string('callback_info')->nullable()->comment('回调内容原始信息');
			$table->integer('account_type')->comment('账户类型：1-余额 2-微信 3-支付宝');
			$table->integer('business_type')->default(0)->comment('业务类型：0-默认 1-充值 2-支付 3-提现 4-退款');
			$table->morphs('order');
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
        Schema::dropIfExists('user_payment_logs');
    }
}
