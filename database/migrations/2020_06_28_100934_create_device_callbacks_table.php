<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeviceCallbacksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('device_callbacks', function (Blueprint $table) {
            $table->id();
            $table->string('park_number')->nullable();
            $table->string('gateway');
            $table->string('type')->nullable()->comment('业务类型：车位变化、地锁、出入场等');
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
        Schema::dropIfExists('device_callbacks');
    }
}
