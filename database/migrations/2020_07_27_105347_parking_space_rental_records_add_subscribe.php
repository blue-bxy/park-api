<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ParkingSpaceRentalRecordsAddSubscribe extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('parking_space_rental_records', function (Blueprint $table) {
            $table->string('rental_user_type')->after('rental_user_id');

            $table->timestamp('start_time')->nullable()->comment('开始时间')->after('fee');
            $table->timestamp('end_time')->nullable()->comment('结束时间')->after('start_time');

            $table->unsignedInteger('expect_amount')->comment('预期金额')->default(0)
                ->after('amount');

            $table->foreignId('car_apt_id')->after('car_rent_id');

            $table->foreign('car_apt_id', 'car_apt_id')
                ->on('car_apts')
                ->references('id')
                ->cascadeOnDelete();

            $table->string('status')->default('pending')
                ->comment('状态：取决于订单状态，仅保留 进行中、已完成2种')->after('amount');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('parking_space_rental_records', function (Blueprint $table) {
            $table->dropColumn('rental_user_type', 'start_time', 'end_time', 'expect_amount', 'status');

            $table->dropForeign('car_apt_id');
            $table->dropColumn('car_apt_id');

        });
    }
}
