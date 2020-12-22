<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParkGatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('park_gates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('park_id')->constrained();
            $table->integer('programme')->default(0)->comment('控制方案：1-科拓云，2-杰停云，3-科拓场库');
            $table->string('brand')->comment('道闸系统品牌');
            $table->string('version')->comment('软件版本');
            $table->integer('mode')->default(0)->comment('对接方式：1-云端转发，2-场库直发');
            $table->integer('payment_mode')->default(0)
                ->comment('停车费电子支付模式：1-场库自收，2-平台代收，3-预约订单平台代收，4-共享车位订单平台代收');
            $table->boolean('is_active')->comment('状态：0-停用，1-启用');
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
        Schema::dropIfExists('park_gates');
    }
}
