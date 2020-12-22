<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParkRateRentSpacesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('park_rate_rent_spaces', function (Blueprint $table) {
            $table->foreignId('park_space_id');
            $table->foreignId('park_rate_id');
            $table->foreignId('car_rent_id');
            $table->index(['park_space_id', 'park_rate_id', 'car_rent_id'],
                'park_space_id_park_rate_id_car_rent_id_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('park_rate_rent_spaces');
    }
}
