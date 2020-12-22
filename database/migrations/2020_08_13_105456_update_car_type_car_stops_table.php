<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateCarTypeCarStopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('car_stops', function (Blueprint $table) {
            $table->unsignedInteger('car_type')->default(0)->comment('车辆类型，0-临时车，1-月租车，2-VIP，7-特殊车辆')->change();
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
