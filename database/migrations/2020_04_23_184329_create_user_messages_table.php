<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_messages', function (Blueprint $table) {
            $table->id();
			$table->unsignedBigInteger('user_id')->comment('用户ID');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
			$table->string('title')->nullable()->comment('消息主题');
            $table->text('content')->nullable()->comment('消息内容');
			$table->string('imgurl')->nullable()->comment('图片信息地址');
			$table->integer('type')->default(0)->comment('消息类型：0-系统通知 1-订单通知 2-活动推广 3-充值提现');
			$table->timestamp('read_time')->nullable()->comment('已读时间');
			$table->morphs('source'); //多态关联-消息来源
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
        Schema::dropIfExists('user_messages');
    }
}
