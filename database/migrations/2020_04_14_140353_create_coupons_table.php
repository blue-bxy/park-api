<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupon_rules', function (Blueprint $table) {
            $table->id();

            $table->string('title')->comment('模板名称');
            $table->text('desc')->nullable()->comment('描述');

            $table->string('type')->comment('类型');
            $table->string('value')->nullable()->comment('规则,n小时，n元，折扣n折,全免');

            $table->morphs('user');

            $table->softDeletes();
            $table->timestamps();
        });

        //针对车场
        Schema::create('coupon_park_rules', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('turnover_rate')->comment('车位周转率');
            $table->string('province_id')->nullable()->index();
            $table->string('city_id')->nullable()->index();
            $table->unsignedInteger('cooperate_days')->comment('合作天数');
            $table->string('park_property')->comment('停车场属性');
            $table->text('desc')->nullable();

            $table->morphs('user');
            $table->softDeletes();
            $table->timestamps();
        });

        // 针对用户
        Schema::create('coupon_user_rules', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->unsignedInteger('active_days')->comment('活跃天数');
            $table->unsignedInteger('low_frequency_days')->default(0)->comment('低频率天数');
            $table->unsignedInteger('regression_days')->default(0)->comment('回归天数');
            $table->boolean('is_new_user')->default(true)->comment('是否新用户');
            $table->text('desc')->nullable();

            $table->morphs('user');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('coupons', function (Blueprint $table) {
			$table->id('id');

			$table->string('no')->unique()->comment('编号')->index();

            $table->string('title')->comment('优惠券标题');
            $table->string('desc')->nullable()->comment('描述');
			$table->string('icon')->nullable()->comment('图片');

			$table->foreignId('park_id')->index();
			$table->foreignId('coupon_rule_id')->index();
			$table->foreignId('coupon_park_rule_id')->nullable()->index();
			$table->foreignId('coupon_user_rule_id')->nullable()->index();

			$table->string('coupon_rule_type')->nullable()->comment('规则类型');
			$table->string('coupon_rule_value')->nullable()->comment('规则值');

            $table->integer('status')->default(0)->comment('0-未使用 1-生效 2-失效 3-已结束');

            $table->unsignedInteger('used_amount')->comment('用券金额');
            $table->unsignedInteger('quota')->comment('配额：发券数量');
            $table->unsignedInteger('max_receive_num')->comment('单个用户领取上限');
            $table->integer('take_count')->comment('已领取的优惠券数量');
            $table->integer('used_count')->comment('已使用的优惠券数量');

            $table->unsignedInteger('need_integral_amount')->default(0)
                ->comment('兑换该优惠券所需积分数');

            $table->timestamp('start_time')->nullable()->comment('发放开始时间');
            $table->timestamp('end_time')->nullable()->comment('发放结束时间');

            $table->timestamp('expired_at')->nullable()->comment('过期时间,有效时间');

			// $table->integer('used')->comment('可用于：1-停车场优惠券 2-租车位');
            // $table->integer('type')->comment('1-小时优免券 2-现金优免券 3-折扣优免券 4-全免券（需要限制大小）');
			// $table->integer('with_amount')->comment('满多少金额');
			// $table->integer('max_receive_num')->comment('领取数量上限');
            // $table->integer('used_amount')->comment('用券金额');

            $table->morphs('publisher'); // 多态关联

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
        Schema::dropIfExists('coupon_user_rules');
        Schema::dropIfExists('coupon_park_rules');
        Schema::dropIfExists('coupon_rules');
        Schema::dropIfExists('coupons');
    }
}
