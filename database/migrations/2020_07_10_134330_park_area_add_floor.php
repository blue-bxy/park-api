<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ParkAreaAddFloor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('park_area', function (Blueprint $table) {
            $table->integer('floor')->default(1)->after('code')->comment('所属楼层');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('park_area', function (Blueprint $table) {
            $table->dropColumn('floor');
        });
    }
}
