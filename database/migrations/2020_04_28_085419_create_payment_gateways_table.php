<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentGatewaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_gateways', function (Blueprint $table) {
            $table->id();
            $table->string('gateway_name')->comment('支付方式');
            $table->string('gateway');
            $table->string('icon');
            $table->string('desc')->nullable();
            $table->integer('sort')->default(0);
            $table->unsignedInteger('max_money')->default(0)->comment('最大额度');
            $table->json('platform')->nullable()->comment('适用场景');
            $table->boolean('enabled')->default(true)->comment('是否开启');
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
        Schema::dropIfExists('payment_gateways');
    }
}
