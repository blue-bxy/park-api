<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserAuthAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_auth_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->index();

            $table->string('from')->comment('wx,qq,ali');
            $table->string('openid');
            $table->string('unionid')->nullable();
            $table->string('nickname');
            $table->string('avatar');
            $table->integer('sex')->default(0)->comment('1男性，2女性，0未知');
            $table->string('province')->nullable()->comment('省份');
            $table->string('city')->nullable()->comment('市');
            $table->string('access_token')->nullable();
            $table->string('access_token_expired_at')->nullable();
            $table->string('refresh_token')->nullable();
            $table->json('raw')->nullable();
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
        Schema::dropIfExists('user_auth_accounts');
    }
}
