<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CarAptOrdersAddSubscribeTime extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('car_apt_orders', function (Blueprint $table) {
            $table->unsignedInteger('subscribe_time')->after('amount')->comment('预约时长');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('car_apt_orders', function (Blueprint $table) {
            $table->dropColumn('subscribe_time');
        });
    }
}
