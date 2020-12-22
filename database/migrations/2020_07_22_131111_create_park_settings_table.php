<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParkSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('park_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('park_id');

            $table->foreign('park_id')->on('parks')->references('id')->cascadeOnDelete();

            $table->string('map_id')->nullable();
            $table->string('map_key')->nullable();

            $table->string('request_url')->nullable()->comment('停车场接口地址');
            $table->string('callback_url')->nullable()->comment('停车场数据接收地址');

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
        Schema::dropIfExists('park_settings');
    }
}
