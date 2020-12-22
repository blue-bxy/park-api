<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UserOrdersAddStopTime extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_orders', function (Blueprint $table) {
            $table->timestamp('car_in_time')->nullable()->comment('进场时间')
                ->after('commented_at');
            $table->timestamp('car_out_time')->nullable()->comment('离场时间')
                ->after('car_in_time');
            $table->timestamp('car_stop_time')->nullable()->comment('停车时间')
                ->after('car_out_time');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_orders', function (Blueprint $table) {
            $table->dropColumn('car_in_time', 'car_out_time', 'car_stop_time');
        });
    }
}
