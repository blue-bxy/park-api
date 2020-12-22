<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreign('user_id')
                ->on('users')
                ->references('id')
                ->onDelete('cascade');

            $table->string('no')->unique()->index();
            $table->morphs('payable');

            $table->unsignedInteger('amount')->default(0)->comment('订单金额');
            $table->unsignedInteger('paid_amount')->default(0)->comment('付款金额');
            $table->string('description');
            $table->string('transaction_id')->index()->comment('流水id');
            $table->string('currency')->index()->default('CNY');
            $table->string('status')->index()->default('pending')->comment('pending/paid/cancelled/failed/refunded');

            $table->string('refund_no')->nullable()->index()->comment('退款订单号');
            $table->string('refund_id')->nullable()->index()->comment('服务商退款单号');
            $table->string('refund_amount')->default(0)->comment('退款金额');
            $table->timestamp('refunded_at')->nullable();

            $table->string('gateway')->index();
            $table->json('gateway_order')->nullable();
            $table->json('context')->nullable();
            $table->json('original_result')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('expired_at')->nullable();
            $table->timestamp('failed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
}
