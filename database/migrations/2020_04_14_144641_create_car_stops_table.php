<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarStopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('car_stops', function (Blueprint $table) {
            $table->id('id')->comment('主键ID');

            $table->foreignId('user_id')->comment('用户ID');
            $table->foreignId('user_car_id');
            $table->foreignId('park_space_id');
            $table->foreignId('user_order_id');
            // $table->string('car_num',10)->comment('用户车牌号')->index('car_num');

            $table->foreignId('park_id')->comment('停车场ID');
            $table->foreign('park_id')->references('id')->on('parks')->onDelete('cascade');

            $table->unsignedInteger('stop_price')->default(0)->comment('实际停车金额');
            $table->unsignedInteger('free_price')->default(0)->comment('优免金额');

            $table->integer('free_type_id')->default(1)->comment('优免类型ID，APP优免，停车场优免');
            $table->integer('car_stop_type')->default(1)->comment('预约停车类型，1表示暂时停车，2表示长租车位，3表示出租暂停');

            $table->unsignedInteger('special_price')->default(0)->comment('特殊处理损失');
            $table->unsignedInteger('washed_price')->default(0)->comment('被冲车辆损失');
            $table->string('car_in_img', 255)->nullable()->comment('汽车入库图片');
            $table->string('car_out_img', 255)->nullable()->comment('汽车出库图片');
            $table->timestamp('car_in_time')->nullable()->comment('汽车入库时间，空表示未识别到')
                ->index('car_in_time');
            $table->timestamp('car_out_time')->nullable()->comment('汽车出库时间，空表示未识别到')
                ->index('car_out_time');

            $table->unsignedInteger('stop_time')->default(0)->comment('停车总时长，单位分');
            $table->integer('pay_stop_type')->default(1)->comment('支付方式：支付宝、微信、钱包、免密（支付宝、微信）、现金');
            $table->timestamp('pay_stop_time')->nullable()->comment('停车费用的支付时间');

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
        Schema::dropIfExists('car_stops');
    }
}
