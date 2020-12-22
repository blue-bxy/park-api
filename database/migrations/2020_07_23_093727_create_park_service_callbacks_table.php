<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParkServiceCallbacksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('park_service_callbacks', function (Blueprint $table) {
            $table->id();

            $table->foreignId('park_id')->nullable();

            $table->string('url')->nullable();

            $table->json('params')->nullable();

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
        Schema::dropIfExists('park_service_callbacks');
    }
}
