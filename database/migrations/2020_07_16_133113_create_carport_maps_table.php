<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarportMapsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carport_maps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('park_id');

            $table->foreign('park_id')
                ->on('parks')
                ->references('id')
                ->cascadeOnDelete();

            $table->string('map_id');
            $table->string('map_key');
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
        Schema::dropIfExists('carport_maps');
    }
}
