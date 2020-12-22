<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlatformFinancialRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('platform_financial_records', function (Blueprint $table) {
            $table->id();
            $table->string('platform',30)->nullable()->comment('平台');
            $table->integer('business')->comment('业务名称 1-电子预约费');
            $table->integer('type')->comment('交易类型 1-正常预约费结算 2-延迟预约费结算 3-当天停车费退款 4-车场提现');
            $table->integer('income')->comment('收入');
            $table->integer('spending')->comment('支出');
            $table->integer('balance')->comment('余额');
            $table->timestamp('date')->comment('账单日期');
            $table->string('state')->comment('day-日汇总 month-月汇总');
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
        Schema::dropIfExists('platform_financial_records');
    }
}
