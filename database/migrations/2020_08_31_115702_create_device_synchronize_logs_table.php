<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeviceSynchronizeLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('device_synchronize_logs', function (Blueprint $table) {
            $table->id();
            $table->string('park_number')->nullable();
            $table->string('gateway');
            $table->tinyInteger('type')->default(0)->comment('设备类型：0-无，1-摄像头，2-地锁，3-蓝牙');
            $table->json('result')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('device_synchronize_logs');
    }
}
