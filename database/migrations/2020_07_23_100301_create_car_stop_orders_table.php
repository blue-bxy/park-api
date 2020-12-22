<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarStopOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('car_stop_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable();
            $table->unsignedBigInteger('car_stop_id')->comment('停车表的id');
            $table->foreign('car_stop_id')->on('car_stops')->references('id')->onDelete('cascade');
            $table->unsignedInteger('amount')->comment('停车金额');
            $table->unsignedInteger('service_charge')->default(0)->comment('平台收取的手续费');
            $table->unsignedInteger('stop_time')->comment('停车时长');
            $table->string('no')->unique()->comment('停车订单号');
            $table->foreignId('coupon_id')->nullable()->comment('优免券id');

            $table->string('payment_gateway')
                ->nullable()
                ->comment('支付方式：支付宝、微信、钱包、免密（支付宝、微信）、现金');

            $table->string('transaction_id')->nullable()->index()->comment('流水id');
            $table->string('currency')->index()->default('CNY');
            $table->string('status')->index()->default('pending')->comment('pending/paid/cancelled/failed/refunded/finished');
            $table->boolean('is_renewal')->default(false);
            $table->unsignedInteger('refund_amount')->default(0)->comment('退款金额');
            $table->string('refund_no')->nullable()->index()->comment('停车退款订单号');
            $table->string('refund_id')->nullable()->index()->comment('停车退款交易号');

            $table->timestamp('expired_at')->nullable()
                ->comment('订单失效时间，超过创建订单时间+30分钟订单失效');
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamp('refunded_at')->nullable();
            $table->timestamp('finished_at')->nullable();
            $table->timestamp('failed_at')->nullable();
            $table->timestamp('commented_at')->nullable();

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
        Schema::dropIfExists('car_stop_orders');
    }
}
