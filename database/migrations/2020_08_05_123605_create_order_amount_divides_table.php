<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderAmountDividesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_amount_divides', function (Blueprint $table) {
            $table->id();

            $table->foreignId('park_id');
            $table->foreign('park_id')->on('parks')->references('id')->cascadeOnDelete();

            // $table->foreignId('order_id');
            // $table->foreign('order_id')->on('user_orders')->references('id')->cascadeOnDelete();

            $table->foreignId('user_id');
            $table->foreign('user_id')->on('users')->references('id')->cascadeOnDelete();

            // 停车费与预约费的实例 carStopOrder,CarApt
            $table->morphs('model');

            $table->unsignedInteger('total_amount')->comment('订单金额');

            $table->unsignedInteger('fee')->default(0)->comment('预约费/停车费，实际金额');
            $table->boolean('fee_type')->default(0)->comment('费用类型：0预约费，1停车费');

            $table->decimal('platform_rate', 5, 2)->default(0)->comment('物业抽成比例');
            $table->decimal('park_rate', 5, 2)->default(0)->comment('物业抽成比例');
            $table->decimal('owner_rate', 5, 2)->default(0)->comment('业主抽成比例');
            //1、 预约费部分：
            // 物业发布车位：物业/平台分成
            // 业主出租车位：物业/平台/业主三方分成
            // 2、 停车费部分：
            // 物业发布车位：物业/平台分成
            // 业主出租车位：物业/平台/业主三方分成
            $table->unsignedInteger('platform_fee')->default(0)->comment('平台收益');
            $table->unsignedInteger('park_fee')->default(0)->comment('物业收益');
            $table->unsignedInteger('owner_fee')->default(0)->comment('业主收益');



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
        Schema::dropIfExists('order_amount_divides');
    }
}
