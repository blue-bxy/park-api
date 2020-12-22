<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCarStopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('car_stops', function (Blueprint $table) {
            $table->unsignedTinyInteger('car_type')->default(1)->comment('车辆类型，1-临时车，2-月租车，3-VIP，4-特殊车辆');
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
            $table->dropColumn('car_type');
        });
    }
}
