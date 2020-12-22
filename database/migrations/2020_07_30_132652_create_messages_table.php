<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->string('no')->unique()->index()->comment('消息编号，自动生成');

            $table->tinyInteger('send_type')
                ->default(0)->comment('发送类型：0:站内系统通知，1:App通知, 2:都发');

            $table->tinyInteger('type')->default(0)->comment('消息类型：优惠券4、系统0');

            $table->string('title')->nullable()->comment('通知标题, 可选');
            $table->string('content')->comment('通知内容');

            $table->string('platform')->default('all')
                ->comment('推送平台：全部all, 苹果ios, 安卓android');

            // 发送类型：立即、定时
            $table->timestamp('send_time')->nullable()->comment('定时发送时间, 非空时定时，反之立即发送');

            $table->json('extras')->nullable()->comment('拓展字段');
            // 针对优惠券类型 拓展字段：面额、有效时间、优惠限制
            // 针对ios平台 区分开发环境 apns_production

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
        Schema::dropIfExists('messages');
    }
}
