<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CarStopsAddCarStopTime extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('car_stops', function (Blueprint $table) {
            $table->boolean('has_find_car')
                ->default(false)
                ->after('stop_time')
                ->comment('是否寻车流程结束');

            $table->timestamp('car_stop_time')->nullable()
                ->after('car_in_time')
                ->comment('车辆停车时间，由摄像头通知');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('car_stops', function (Blueprint $table) {
            $table->dropColumn('car_stop_time', 'has_find_car');
        });
    }
}
