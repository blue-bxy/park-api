<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateParkStallsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::table('park_stalls', function (Blueprint $table) {
//            $table->dropColumn(['expect_temporary_parking_count','do_business_time']);
//        });
        Schema::table('park_stalls', function (Blueprint $table) {
            $table->integer('order_carport')->comment("预约车位")->after('longtime_carport_count');
            $table->integer('charging_pile_carport')->comment("充电桩车位")->after('longtime_carport_count');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('park_stalls', function (Blueprint $table) {
            $table->dropColumn(['order_carport','charging_pile_carport']);
        });
    }
}
