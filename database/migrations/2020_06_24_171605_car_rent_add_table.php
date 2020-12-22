<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CarRentAddTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('car_rents', function (Blueprint $table) {
            $table->string('start')->nullable()->comment('出租时间段的起始时间')->after('rent_price');
            $table->string('stop')->nullable()->comment('出租时间段的结束时间')->after('start');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('car_rents', function (Blueprint $table) {
            $table->dropColumn('start');
            $table->dropColumn('stop');
        });
    }
}
