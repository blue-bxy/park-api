<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParkAreaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('park_area', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->comment('区域名称');
            $table->string('attribute', 50)->comment('区域属性');
            $table->integer('parking_places_count')->default(0)
                ->comment('车位总数');
            $table->integer('long_term_parking_places_count')->default(0)
                ->comment('长租车位数');
            $table->integer('reserved_parking_places_count')->default(0)
                ->comment('预约车位数');
            $table->integer('temp_parking_places_count')->default(0)
                ->comment('临时车位数');
            $table->foreignId('park_id')->comment('外键，关联parks表id');
            $table->foreign('park_id')->on('parks')
                ->references('id')->onDelete('cascade');
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
        Schema::dropIfExists('park_area');
    }
}
