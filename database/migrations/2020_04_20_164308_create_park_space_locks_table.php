<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParkSpaceLocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('park_space_locks', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->comment('地锁名称');
            $table->string('location', 50)->comment('地锁位置');
            $table->string('brand', 100)->nullable()->comment('品牌');
            $table->string('model', 100)->nullable()->comment('型号');
            $table->string('ip', 48)->comment('ip地址');
            $table->string('protocol', 50)->comment('通信协议');
            $table->string('gateway', 48)->comment('网关');
            $table->boolean('status')->default(false)
                ->comment('蓝牙状态，1-开启，0-关闭');
            $table->boolean('network_status')->default(false)
                ->comment('网络状态，1-开启，0-关闭');
            $table->string('remark')->nullable()->comment('备注');
            $table->foreignId('park_id')->comment('外键，关联parks表id');
            $table->foreign('park_id')
                ->on('parks')
                ->references('id')
                ->onDelete('cascade');
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
        Schema::dropIfExists('park_space_locks');
    }
}
