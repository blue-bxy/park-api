<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserBalancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_balances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');

            $table->foreign('user_id')
                ->on('users')
                ->references('id')
                ->onDelete('cascade');

            $table->string('order_no')->index();
            $table->string('trade_no')->nullable()->index();

            $table->integer('trade_type')->comment('交易方式:收入1，支出2');

            $table->unsignedInteger('amount')->default(0)->comment('操作金额');
            $table->unsignedInteger('balance')->default(0)->comment('余额（实时）');
            $table->unsignedInteger('fee')->default(0)->comment('手续费');
            $table->string('type')->nullable()->comment('业务类型');

            $table->string('body')->nullable()->comment('描述');
            $table->string('gateway')->nullable()->comment('付款方式');

            $table->unsignedInteger('status')->default(1)->comment('状态：1成功,2失败，0申请中（针对提现）');

            $table->morphs('order');

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
        Schema::dropIfExists('user_balances');
    }
}
