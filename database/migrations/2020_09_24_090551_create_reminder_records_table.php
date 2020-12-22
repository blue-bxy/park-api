<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReminderRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reminder_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reminder_id')->comment('reminder的id');
            $table->foreignId('admin_id')->nullable()->comment('操作人员，系统自动通知就为空');
            $table->string('state')->default('')->comment('2-推送通知，3-短信，4-人工催收');
            $table->string('feedback')->nullable()->comment('催收反馈信息');
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
        Schema::dropIfExists('reminder_records');
    }
}
