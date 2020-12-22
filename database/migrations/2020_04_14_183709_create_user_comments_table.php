<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_comments', function (Blueprint $table) {
            $table->id();
			$table->unsignedBigInteger('user_id')->comment('用户ID');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
			$table->unsignedBigInteger('order_id')->comment('订单ID');
            $table->foreign('order_id')->references('id')->on('user_orders')->onDelete('cascade');
            $table->text('content')->nullable()->comment('评价内容');
			$table->string('imgurl')->nullable()->comment('评论上传图片');
			$table->integer('rate')->default('5')->comment('综合评价星级 1-5星');
			$table->integer('is_display')->default('1')->comment('是否展示 0-不展示 1-展示');
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
        Schema::dropIfExists('user_comments');
    }
}
