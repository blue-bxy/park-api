<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddManufacturingModeToParkAreaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('park_area', function (Blueprint $table) {
            $table->boolean('manufacturing_mode')->default(0)->after('code')
                ->comment('0-无对接，1-道闸，2-道闸+室内导航，3-道闸+车位摄像头+室内导航');
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
            $table->dropColumn('manufacturing_mode');
        });
    }
}
