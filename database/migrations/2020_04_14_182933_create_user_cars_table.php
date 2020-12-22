<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserCarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_cars', function (Blueprint $table) {
            $table->id();
			$table->unsignedBigInteger('user_id')->comment('用户ID');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('owner_name')->nullable()->comment('车主姓名');
			$table->string('car_number')->comment('车牌号');
			$table->string('frame_number')->comment('车架号');
			$table->string('engine_number')->comment('发动机号');
			$table->string('brand_model')->nullable()->comment('品牌型号');
			$table->string('face_license_imgurl')->nullable()->comment('驾驶证正面图片url');
			$table->string('back_license_imgurl')->nullable()->comment('驾驶证反面图片url');
			$table->integer('is_default')->default(0)->comment('是否设置为默认车牌 1:默认车牌 0：不默认');
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
        Schema::dropIfExists('user_cars');
    }
}
