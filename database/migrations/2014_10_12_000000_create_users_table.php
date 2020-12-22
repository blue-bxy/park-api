<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nickname')->comment('昵称');
            $table->string('mobile')->unique();
            $table->string('password');
			$table->string('headimgurl')->nullable()->comment('头像');
			$table->string('email')->nullable()->comment('邮箱');
			$table->string('address')->nullable()->comment('常用地址');
			$table->integer('sex')->default(0)->comment('性别：0-默认 1-男 2-女');
			$table->integer('integral')->default(0)->comment('积分');
			$table->integer('balance')->default(0)->comment('余额');
            $table->boolean('is_verify')->default(false);
            $table->timestamp('verified_at')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
