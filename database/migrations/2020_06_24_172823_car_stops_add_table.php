<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CarStopsAddTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('car_stops', function (Blueprint $table) {
            $table->string('car_num')->comment('停车的车牌号')->after('user_car_id');
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
            $table->dropColumn('car_num');
        });
    }
}
