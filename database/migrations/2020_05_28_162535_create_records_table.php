<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('records', function (Blueprint $table) {
            $table->id();
            $table->string('record_no')->unique()->comment('调整单号');
            $table->foreignId('withdrawal_id');
            $table->foreign('withdrawal_id')
                ->references('id')
                ->on('withdrawals')
                ->onDelete('cascade');
            $table->integer('adjust_amount')->comment('调整金额');
            $table->integer('adjust_type')->default(1)->comment('调整类型 1-结算扣款 2-结算补款');
            $table->string('reason',30)->nullable()->comment('调整原因');
            $table->integer('is_loss')->default(1)->comment('公司是否亏损 1-否 2-是');
            $table->string('operator',20)->comment('操作员');
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
        Schema::dropIfExists('records');
    }
}
