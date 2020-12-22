<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * 出租车位迁移文件
 */
class CreateCarRentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('car_rents', function (Blueprint $table) {
            $table->id();
            // $table->foreignId('user_id')->comment('发布车位车主的ID');
            $table->foreignId('park_id')->comment('项目ID')->index();

            $table->foreignId('park_space_id')->comment('外键，关联park_spaces的id')->index();
            // $table->foreign('park_space_id')->on('park_spaces')
            //     ->references('id')->onDelete('cascade');

            $table->string('car_num')->nullable()->comment('停车的车牌号');

            $table->string('rent_num')->nullable()->comment('出租车位编号');

            $table->json('pics')->nullable()->comment('图片');

            $table->unsignedInteger('rent_price')->default(0)->comment('出租单价');
            $table->timestamp('rent_start_time')->nullable()->comment('出租开始时间');
            $table->timestamp('rent_end_time')->nullable()->comment('出租结束时间');
            $table->boolean('rent_status')->default(0)
                ->comment('出租车位状态，0表示停用，1表示启用');
            $table->unsignedInteger('rent_type_id')->default(1)
                ->comment('出租车位的类型，1表示物业出租，2表示业主出租');

            $table->morphs('user');

            $table->string('rent_no')->comment('总的订单号')->index();

            $table->unsignedInteger('rent_time')->default(0)->comment('出租总时长');
            $table->unsignedInteger('rent_all_price')->default(0)->comment('出租总价格');

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
        Schema::dropIfExists('car_rents');
    }
}
