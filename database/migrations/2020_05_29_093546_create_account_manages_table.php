<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountManagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_manages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('park_id')->comment('停车场id');
            $table->foreignId('property_id')->comment('物业id');
            $table->string('account_name')->comment('账号的用户名');
            $table->string('account')->comment('账号');
            $table->unsignedTinyInteger('account_type')->comment('账户类型:1-对公，2-对私');
            $table->string('account_province')->comment('账号所在的省份');
            $table->string('account_city')->comment('账号所在城市');
            $table->string('bank_name')->comment('开户行');
            $table->string('sub_branch')->comment('支行');
            $table->foreignId('contract_id')->comment('合同编号的id');
            $table->unsignedTinyInteger('synchronization_type')->comment('同步状态:0-未同步，1-已同步');
            $table->unsignedTinyInteger('audit_status')->default(0)->comment('审核状态:0-未审核，1-已审核');
            $table->timestamp('banned_withdraw')->nullable()->comment('冻结提现');
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
        Schema::dropIfExists('account_manages');
    }
}
