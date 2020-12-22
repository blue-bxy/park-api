<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUserCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_coupons', function (Blueprint $table) {
            $table->string('no')->comment('编号')->after('id');
            $table->unsignedBigInteger('order_id')->default(0)
                ->comment('外键，关联user_order表id')->after('coupon_id')->index();
            $table->integer('distribution_method')->default(0)
                ->comment('发放方式：1-平台推送，2-app二维码，3-微信/支付宝二维码，4-分享链接')->after('end_time');
            $table->string('status')->default('pending')
                ->comment("状态：pending正常，used已使用，expired失效，invalid作废")->change();
        });
        $coupons = \Illuminate\Support\Facades\DB::table('user_coupons')->where('no', '=', '')
            ->select(['id', 'no'])->get();
        $coupons->map(function ($coupon) {
            \Illuminate\Support\Facades\DB::table('user_coupons')->where('id', '=', $coupon->id)
                ->update(['no' => $coupon->id]);
        });
        Schema::table('user_coupons', function (Blueprint $table) {
            $table->unique(['no']);
        });

        Schema::table('coupons', function (Blueprint $table) {
            $table->integer('distribution_method')->default(0)
                ->comment('发放方式：1-平台推送，2-app二维码，3-微信/支付宝二维码，4-分享链接')->after('end_time');
        });

        Schema::table('coupon_user_rules', function (Blueprint $table) {
            $table->renameColumn('low_frequency_days', 'activity_setting_days');
        });
        Schema::table('coupon_user_rules', function (Blueprint $table) {
            $table->boolean('is_activity_active')->default(1)
                ->comment('活跃度启用状态：0-不启用，1-启用')->after('title');
            $table->boolean('is_regression_active')->default(1)
                ->comment('回归启用状态：0-不启用，1-启用')->after('activity_setting_days');
            $table->unsignedInteger('activity_setting_days')->default(0)->comment('活跃度设置天数')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_coupons', function (Blueprint $table) {
            $table->dropColumn(['no', 'order_id', 'distribution_method']);
        });

        Schema::table('coupons', function (Blueprint $table) {
            $table->dropColumn('distribution_method');
        });

        Schema::table('coupon_user_rules', function (Blueprint $table) {
            $table->dropColumn(['is_activity_active', 'is_regression_active']);
            $table->unsignedInteger('activity_setting_days')->default(0)->comment('低频率天数')->change();
        });
        Schema::table('coupon_user_rules', function (Blueprint $table) {
            $table->renameColumn('activity_setting_days', 'low_frequency_days');
        });
    }
}
