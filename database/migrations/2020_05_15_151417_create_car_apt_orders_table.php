<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarAptOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('car_apt_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('car_apt_id')->comment('预约表的id');
            $table->foreign('car_apt_id')->on('car_apts')->references('id')->onDelete('cascade');
            $table->unsignedInteger('amount')->comment('预约金额');
            $table->string('no')->unique()->index()->comment('预约订单号');
            $table->foreignId('coupon_id')->nullable();

            $table->string('payment_gateway')
                ->nullable()
                ->comment('支付方式：支付宝、微信、钱包、免密（支付宝、微信）、现金');

            $table->string('transaction_id')->nullable()->index()->comment('流水id');
            $table->string('currency')->index()->default('CNY');
            $table->string('status')->index()->default('pending')->comment('pending/paid/cancelled/failed/refunded/finished');
            $table->unsignedInteger('refund_amount')->default(0)->comment('退款金额');
            $table->string('refund_no')->nullable()->index()->comment('预约退款订单号');
            $table->string('refund_id')->nullable()->index()->comment('预约退款交易号');

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
        Schema::dropIfExists('car_apt_orders');
    }
}
