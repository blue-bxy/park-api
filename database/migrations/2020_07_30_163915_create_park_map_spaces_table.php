<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParkMapSpacesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('park_map_spaces', function (Blueprint $table) {
            $table->id();
            $table->string('unique_id', 64)->comment('唯一编号');
            $table->string('number')->comment('车位编号');
            $table->tinyInteger('floor')->comment('楼层');
            $table->string('area_code')->comment('区域编号');
            $table->foreignId('park_id')->comment('关联parks表id');
            $table->foreignId('park_area_id')->comment('关联park_area表id');
            $table->foreign('park_id')->on('parks')
                ->references('id')->onDelete('cascade');
            $table->foreign('park_area_id')->on('park_area')
                ->references('id')->onDelete('cascade');
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
        Schema::dropIfExists('park_map_spaces');
    }
}
