<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessageCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('message_codes', function (Blueprint $table) {
            $table->id('id');

            $table->string('action_type')
                ->comment('操作类型：login:注册，bind:绑定手机号，confirm:验证手机号，forget:忘记密码');

            $table->string('phone')->comment('手机号');
            $table->string('code')->nullable()->comment('验证码');

            $table->ipAddress('ip')->nullable();

            $table->string('sid')->nullable()->comment('发送标识 id');//2019:5567324082361780698
            $table->timestamp('send_time')->nullable()->comment('转发时间');
            $table->boolean('report_status')->default(true)
                ->comment('短信接收状态，true（成功）、false（失败）');

            $table->timestamp('report_time')->nullable()->comment('用户接收时间');
            $table->json('report')->nullable()->comment('回调内容');

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
        Schema::dropIfExists('message_codes');
    }
}
