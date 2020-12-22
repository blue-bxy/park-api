<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRemindersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reminders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_order_id')->comment('user_order表的id');
            $table->foreignId('park_id')->nullable()->comment('车场id');
            $table->foreignId('park_space_id')->nullable()->comment('车位id');
            $table->foreignId('car_stop_id')->nullable()->comment('停车记录的id');
            $table->foreignId('user_id')->nullable()->comment('该订单用户的id');
            $table->foreignId('user_car_id')->nullable()->comment('用户车辆id');
            $table->string('order_no')->default('')->comment('订单号');
            $table->string('phone')->default('')->comment('用户注册手机号');
            $table->string('car_num')->default('')->comment('车牌号');
            $table->timestamp('car_in_time')->nullable()->comment('车辆进场时间');
            $table->timestamp('car_out_time')->nullable()->comment('车辆出场时间');
            $table->unsignedInteger('stop_time')->default(0)->comment('停车时长');
            $table->unsignedInteger('amount')->default(0)->comment('停车金额');
            $table->unsignedInteger('deduct_amount')->default(0)->comment('实收金额');
            $table->unsignedInteger('days_overdue')->default(0)->comment('逾期天数');
            $table->string('state')->default('1')->comment('1-未催收，2-推送通知，3-短信，4-人工催收');
            $table->string('pay_status')->default('pending')->comment('支付状态：pending-未支付；paid-已经支付');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reminders');
    }
}
