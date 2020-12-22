<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWithdrawalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('withdrawals', function (Blueprint $table) {
            $table->id();
            $table->string('withdrawal_no')->unique()->comment('提现单编号');
            $table->integer('person_type')->comment('提取人员类型 1-物业提现 2-车主提现');
            $table->timestamp('apply_time')->comment('申请时间');
            $table->unsignedInteger('apply_money')->comment('申请金额');

            $table->integer('status')->default(1)->comment('1-待处理 2-汇款中 3-已完成');
            // $table->string('applicant')->comment('申请人');
            $table->string('account')->nullable()->comment('账户');

            $table->foreignId('park_id')->nullable();
            $table->foreign('park_id')->on('parks')->references('id')->onDelete('cascade');
            // $table->unsignedBigInteger('property_id');
            // $table->foreign('property_id')->on('properties')->references('id')->onDelete('cascade');
            $table->morphs('user');

            $table->foreignId('admin_id')->nullable()->comment('审核人');

            $table->timestamp('audit_time')->nullable()->comment('审核时间');

            $table->string('remark')->nullable()->comment('备注');

            $table->timestamp('completion_time')->nullable()->comment('完成时间');

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
        Schema::dropIfExists('withdrawals');
    }
}
