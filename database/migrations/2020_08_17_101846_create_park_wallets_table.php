<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParkWalletsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 停车场钱包
        Schema::create('park_wallets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('park_id');
            $table->foreign('park_id')
                ->on('parks')->references('id')->cascadeOnDelete();

            $table->unsignedInteger('amount')->default(0)->comment('总金额:预约费+停车费');

            $table->unsignedInteger('reserve_fee')->default(0)->comment('预约费');
            $table->unsignedInteger('parking_fee')->default(0)->comment('停车费');

            $table->unsignedInteger('withdrawal')->default(0)->comment('提现总金额');

            $table->softDeletes();
            $table->timestamps();
        });

        // 停车场 钱包流水
        Schema::create('park_wallet_balances', function (Blueprint $table) {
            $table->id();

            $table->foreignId('park_id');
            $table->foreign('park_id')
                ->on('parks')->references('id')->cascadeOnDelete();

            $table->unsignedInteger('amount')->default(0);
            $table->string('type')->comment('费用类型：停车费、预约费、提现');
            $table->tinyInteger('trade_type')->comment('交易方式:1收入,2支出');

            $table->unsignedInteger('balance')->default(0)->comment('余额');
            $table->string('order_no')->nullable()->comment('订单交易号');

            $table->nullableMorphs('order');

            $table->softDeletes();
            $table->timestamps();
        });

        // 账单汇总
        Schema::create('park_bill_summaries', function (Blueprint $table) {
            $table->id();

            $table->foreignId('park_id');
            $table->foreign('park_id')
                ->on('parks')->references('id')->cascadeOnDelete();

            $table->string('date')->comment('汇总日期：Y-m-d/Y-m');
            $table->string('type')->default('day')->comment('账单日期类型:天:day,月:month,年:year');

            $table->string('bill_type')->comment('业务类型:汇总...');

            $table->unsignedInteger('amount')->default(0)->comment('总额');
            $table->unsignedInteger('income')->default(0)->comment('收入');
            $table->unsignedInteger('expenses')->default(0)->comment('支出');

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
        Schema::dropIfExists('park_bill_summaries');
        Schema::dropIfExists('park_wallet_balances');
        Schema::dropIfExists('park_wallets');
    }
}
