<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_orders', function (Blueprint $table) {
            $table->id('id');
            $table->string('order_no')->unique()->comment('订单编号');

            $table->foreignId('user_id');

			$table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

			$table->foreignId('park_id')->comment('停车场ID');

            $table->foreign('park_id')
                ->references('id')
                ->on('parks')
                ->onDelete('cascade');

			$table->foreignId('coupon_id')->nullable()->comment('优惠券ID');
            $table->foreign('coupon_id')
                ->references('id')
                ->on('coupons')
                ->onDelete('cascade');

			$table->foreignId('car_stop_id')->nullable()->comment('停车记录ID');
            $table->foreign('car_stop_id')
                ->references('id')
                ->on('car_stops')
                ->onDelete('cascade');

            $table->foreignId('user_car_id')->nullable()->index();

            // 预约id
            $table->foreignId('car_apt_id')->nullable()->index();

            $table->unsignedInteger('subscribe_amount')->default(0)
                ->comment('预约定金');
            // 停车费用
            $table->unsignedInteger('amount')->comment('停车费用');// 出厂前结算
            $table->unsignedInteger('discount_amount')->default(0)
                ->comment('优惠费用');
            $table->unsignedInteger('refund_amount')->default(0)
                ->comment('退款金额');
            $table->unsignedInteger('total_amount')->comment('总金额，结算金额');
            $table->string('payment_no')->nullable()->comment('交易号');

			$table->string('payment_gateway')
                ->nullable()
                ->comment('支付方式：支付宝、微信、钱包、免密（支付宝、微信）、现金');

			$table->string('status')->index()->default('pending')->comment('pending/paid/cancelled/failed/refunded/finished/commented');

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
        Schema::dropIfExists('user_orders');
    }
}
