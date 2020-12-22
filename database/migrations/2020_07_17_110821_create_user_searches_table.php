<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserSearchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_searches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');

            $table->foreign('user_id')->on('users')->references('id')->cascadeOnDelete();

            $table->string('keyword');

            $table->unsignedInteger('click_num')->default(0);

            $table->string('longitude')->nullable();
            $table->string('latitude')->nullable();

            $table->softDeletes();
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
        Schema::dropIfExists('user_searches');
    }
}
