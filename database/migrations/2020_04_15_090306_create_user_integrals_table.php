<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserIntegralsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_integrals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id'); // 新特性 等同于以下
            // $table->unsignedBigInteger('user_id')->comment('用户ID');
            $table->foreign('user_id')->on('users')->references('id')->onDelete('cascade');
            $table->integer('operation')->default(0)->comment('操作'); // 加、减
            $table->integer('integral_num')->nullable()->comment('操作积分数');
            $table->integer('balance')->nullable()->comment('剩余积分数');

            $table->morphs('order'); // 多态关联
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
        Schema::dropIfExists('user_integrals');
    }
}
