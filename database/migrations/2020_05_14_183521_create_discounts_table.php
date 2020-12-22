<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiscountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->string('project_name')->comment("项目名称");
            $table->string('discount_coupon',100)->comment("优惠券名称");
            $table->tinyInteger('discount_type')->default(1)->comment("优免类型：1小时优免券，2现金优免券，3折扣优免券，4全免券");
            $table->string('rule_hour')->nullable()->comment("优免小时");
            $table->string('rule_money')->nullable()->comment("优免元");
            $table->string('rule_discount')->nullable()->comment("优免折");
            $table->tinyInteger('merchant_type')->default(1)->comment("商家类型");
            $table->tinyInteger('select_profile_type')->default(1)->comment("选择用户类型");
            $table->tinyInteger('issue_form')->default(1)->comment("发放形式: 1电子券");
            $table->tinyInteger('user_issue_count')->comment("单个用户发放张数");
            $table->date('issue_start_date')->comment("优惠券发放起始时间");
            $table->date('issue_end_date')->comment("优惠券发放起止时间");
            $table->string('discount_coupon_number')->comment("优惠券编号");
            $table->tinyInteger('use_state')->nullable()->comment("使用状态");
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
        Schema::dropIfExists('discounts');
    }
}
