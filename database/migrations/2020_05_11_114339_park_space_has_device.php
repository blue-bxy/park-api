<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ParkSpaceHasDevice extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('park_space_has_devices', function (Blueprint $table) {
            $table->foreignId('park_space_id');

            $table->foreign('park_space_id')
                ->on('park_spaces')
                ->references('id')->onDelete('cascade');

            $table->morphs('device');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('park_space_has_devices');
    }
}
