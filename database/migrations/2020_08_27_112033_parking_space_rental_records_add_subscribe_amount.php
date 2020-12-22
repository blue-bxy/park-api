<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ParkingSpaceRentalRecordsAddSubscribeAmount extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('parking_space_rental_records', function (Blueprint $table) {
            $table->unsignedInteger('subscribe_amount')->default(0)->after('amount')->comment('预约费');

            $table->unsignedInteger('stop_amount')->default(0)
                ->comment('停车费')->after('subscribe_amount');

            $table->timestamp('subscribe_end_time')->nullable()->comment('预约结束时间')
                ->after('end_time');
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
            $table->dropColumn('subscribe_amount', 'stop_amount', 'subscribe_end_time');
        });
    }
}
