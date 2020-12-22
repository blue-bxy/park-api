<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddElectricToParkBluetoothTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('park_bluetooth', function (Blueprint $table) {
            $table->decimal('electric', 6, 2)->nullable()
                ->after('gateway')->comment('电量（百分比）');
            $table->string('error')->default('正常')
                ->after('network_status')->comment('故障信息');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('park_bluetooth', function (Blueprint $table) {
            $table->dropColumn('electric');
        });
    }
}
