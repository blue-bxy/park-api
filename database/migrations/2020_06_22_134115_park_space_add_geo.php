<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ParkSpaceAddGeo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('park_spaces', function (Blueprint $table) {
            $table->string('longitude')->nullable()->comment('经度')->after('number');
            $table->string('latitude')->nullable()->comment('纬度')->after('longitude');
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('park_spaces', function (Blueprint $table) {
            $table->dropColumn('latitude', 'longitude');
        });
    }
}
