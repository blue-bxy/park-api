<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applies', function (Blueprint $table) {
            $table->id();
            $table->string('no')->unique()->comment('付款批次号');
            $table->integer('amount')->comment('申请付款金额');
            $table->integer('payment_number')->comment('申请付款笔数');
            $table->integer('success_number')->nullable()->comment('成功付款笔数');
            $table->integer('business_type')->comment('业务类型 1-提现 2-退款');
            $table->integer('person_type')->comment('付款对象类型 1-物业 2-用户');
            $table->integer('submit')->default(1)->comment('提交结果 1-待提交 2-已提交 3-已拒绝');
            $table->integer('status')->default(1)->comment('处理状态 1-待处理 2-处理中 3-已处理');
            $table->timestamp('apply_time')->comment('申请时间');
            $table->timestamp('payment_time')->nullable()->comment('付款时间');
            $table->timestamp('complete_time')->nullable()->comment('完成日期');
            $table->string('agent',30)->nullable()->comment('经办人');
            $table->string('channel',30)->nullable()->comment('付款通道');
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
        Schema::dropIfExists('applies');
    }
}
