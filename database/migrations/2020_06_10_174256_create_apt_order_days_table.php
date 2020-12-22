<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAptOrderDaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('apt_order_days', function (Blueprint $table) {
            $table->id();
            $table->foreignId('park_id');
            $table->string('no')->comment('结算单号');
            $table->integer('type')->comment('结算类型 1-正常结算收入 2-延时结算收入 3-退款');
            $table->integer('amount')->comment('总金额');
            $table->timestamp('time')->comment('结算时间');
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
        Schema::dropIfExists('apt_order_days');
    }
}
