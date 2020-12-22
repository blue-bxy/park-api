<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserComplaintsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_complaints', function (Blueprint $table) {
            $table->id();
			$table->unsignedBigInteger('user_id')->comment('用户ID');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
			$table->string('title')->nullable()->comment('投诉主题');
            $table->text('content')->nullable()->comment('投诉内容');
			$table->string('imgurl')->nullable()->comment('投诉上传图片（多张使用逗号隔开）');
			$table->integer('type')->default('0')->comment('投诉类型 0-投诉个人用户（占车位） 1-投诉商家、物业（服务态度）');
			$table->integer('result')->default('0')->comment('处理结果： 0-未解决 1-已解决');
			$table->integer('urgencydegree')->default('0')->comment('紧急程度： 0-默认 1-一般 2-紧急 3-非常紧急');
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
        Schema::dropIfExists('user_complaints');
    }
}
