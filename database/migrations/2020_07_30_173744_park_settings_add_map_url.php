<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ParkSettingsAddMapUrl extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('park_settings', function (Blueprint $table) {
            $table->string('map_find_car_url')->nullable()->after('map_key');
            $table->string('map_find_parking_url')->nullable()->after('map_find_car_url');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('park_settings', function (Blueprint $table) {
            $table->dropColumn('map_find_car_url', 'map_find_parking_url');
        });
    }
}
