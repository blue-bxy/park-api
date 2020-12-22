<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParkVirtualSpacesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('park_virtual_spaces', function (Blueprint $table) {
            $table->id();
            $table->string('code')->comment('唯一编号');
            $table->string('number')->comment('车位编号');
            $table->string('pic')->nullable()->comment('车位图片');
            $table->integer('floor')->default(1)->comment('冗余区域floor');
            $table->boolean('is_stop')->default(0)->comment('车位上是否停车，0-无车，1-有车');
            $table->foreignId('stop_id')->nullable()->index();
            $table->foreignId('park_space_id')->nullable()->comment('外键，关联park_spaces表id');
            $table->foreignId('park_area_id')->comment('外键，关联park_area表id');
            $table->foreignId('park_id')->comment('外键，关联parks表id');
            $table->foreign('park_space_id')->on('park_spaces')
                ->references('id')->onDelete('cascade');
            $table->foreign('park_area_id')->on('park_area')
                ->references('id')->onDelete('cascade');
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
        Schema::dropIfExists('park_virtual_spaces');
    }
}
