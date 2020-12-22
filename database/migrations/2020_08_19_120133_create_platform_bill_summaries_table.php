<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlatformBillSummariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('platform_bill_summaries', function (Blueprint $table) {
            $table->id();

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
        Schema::dropIfExists('platform_bill_summaries');
    }
}
