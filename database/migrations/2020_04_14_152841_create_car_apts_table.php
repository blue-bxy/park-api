<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarAptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('car_apts', function (Blueprint $table) {
            $table->id('id')->comment('主键ID');
            $table->foreignId('user_id')->comment('用户ID');
            $table->foreign('user_id')->on('users')->references('id')->onDelete('cascade');
            $table->foreignId('user_order_id')->index()->comment('外键，关联user_order表id');

            $table->foreignId('park_id')->comment('停车场ID');
            $table->foreign('park_id')->references('id')->on('parks')->onDelete('cascade');

            $table->foreignId('park_space_id');

            $table->foreignId('user_car_id')->index();

            $table->unsignedInteger('amount')->comment('首次支付费用');
            $table->unsignedInteger('total_amount')->comment('总费用');
            $table->unsignedInteger('deduct_amount')->default(0)->comment('实际扣款');
            $table->unsignedInteger('refund_total_amount')->default(0)->comment('退款费用');
            // $table->string('car_num')->comment('用户车牌号')->index('car_num');
            $table->foreignId('car_rent_id')->nullable()->comment('出租车位的id')->index();
            $table->foreignId('car_stop_id')->nullable()->comment('停车记录')->index();
            // $table->foreign('car_rent_id')->on('car_rents')
            //     ->references('id')->onDelete('cascade');

            $table->timestamp('apt_start_time')->nullable()->comment('预约开始时间');
            $table->timestamp('apt_end_time')->nullable()->comment('预约结束时间');
            $table->string('apt_time')->nullable()->comment('预约总时间');

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
        Schema::dropIfExists('car_apts');
    }
}
