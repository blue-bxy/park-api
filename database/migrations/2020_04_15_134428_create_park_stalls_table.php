<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParkStallsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('park_stalls', function (Blueprint $table) {
            $table->unsignedBigInteger('park_id');
            $table->foreign('park_id')->on('parks')->references('id')->onDelete('cascade');
            $table->integer('carport_count')->comment("总车位数");
            $table->integer('longtime_carport_count')->comment("长租车位数");
            $table->integer('temporary_carport_count')->comment("临停车位数");
            $table->integer('lanes_count')->comment("总车道数");
            $table->integer('expect_temporary_parking_count')->comment("预计日临停量");
            $table->timestamp('park_operation_time')->nullable()->comment("停车场运营时间");
            $table->string('do_business_time')->nullable()->comment("营业时间");
            $table->string('fee_string')->comment("文字版费率");
            $table->string('map_fee')->nullable()->comment("地图费率");
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
        Schema::dropIfExists('park_stalls');
    }
}
