<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_devices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable();
            $table->foreign('user_id')->on('users')->references('id')->cascadeOnDelete();

            $table->string('platform')->comment('ios,android');

            $table->string('brand')->nullable()->comment('品牌');
            $table->string('model')->nullable()->comment('型号');
            $table->string('uid')->nullable()->comment('设备id');
            $table->string('version')->nullable()->comment('客户端版本');

            $table->string('jpush_id')->nullable()->comment('极光 用户id');

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
        Schema::dropIfExists('user_devices');
    }
}
