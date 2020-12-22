<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ParkingSpaceRentalRecordsAddStop extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('parking_space_rental_records', function (Blueprint $table) {
            $table->foreignId('stop_id')->nullable()->after('user_car_id')->comment('停车记录');

            $table->foreign('stop_id')->on('car_stops')->references('id')->cascadeOnDelete();
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
            $table->dropColumn('stop_id');
        });
    }
}
