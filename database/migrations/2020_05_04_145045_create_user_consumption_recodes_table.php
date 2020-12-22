<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserConsumptionRecodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_consumption_recodes', function (Blueprint $table) {
            $table->id('id')->comment('主键ID');
			$table->string('serial_number')->comment('流水号');
			$table->string('car_number')->comment('车牌号');
			$table->string('park_name')->comment('停车场');
			$table->integer('amount')->comment('金额');
			$table->integer('payment_channel')->comment('支付通道：1-余额 2-微信 3-支付宝');
			$table->integer('payment_type')->default(0)->comment('支付类型：0-默认值 1-余额支付 2-网关支付 3-快捷支付 4-二维码支付 5-代付 6-代扣');
			$table->string('payment_account')->nullable()->comment('支付账户');
			$table->string('channel_transaction_no')->nullable()->comment('通道交易号');
			$table->integer('business_type')->comment('业务类型： 1-支付 2-提现 3-退款');
			$table->string('status')->index()->default('pending')->comment('pending/paid/cancelled/failed/refunded');
			$table->morphs('user');
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
        Schema::dropIfExists('user_consumption_recodes');
    }
}
