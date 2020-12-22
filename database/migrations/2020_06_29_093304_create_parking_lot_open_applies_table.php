<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParkingLotOpenAppliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parking_lot_open_applies', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->nullable()->index();
            $table->string('nickname');
            $table->string('telephone');

            $table->string('village_name')->comment('小区名称');
            //小区地址 联系人姓名 联系人手机 物业联系方式
            // 省市区
            $table->string('village_province')->comment('省');
            $table->string('village_city')->comment('市');
            $table->string('village_country')->comment('区');
            $table->string('village_address')->comment('详细地址');

            $table->string('village_telephone')->nullable()->comment('小区联系方式');

            $table->string('longitude')->nullable();
            $table->string('latitude')->nullable();

            $table->foreignId('park_id')->nullable()->index();

            //
            $table->string('status')->default(\App\Models\Parks\ParkingLotOpenApply::STATUS_PENDING)
                ->comment('状态');
            $table->foreignId('admin_id')->nullable()->index();

            $table->timestamp('processed_at')->nullable()->comment('受理时间');

            $table->timestamp('finished_at')->nullable();


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
        Schema::dropIfExists('parking_lot_open_applies');
    }
}
