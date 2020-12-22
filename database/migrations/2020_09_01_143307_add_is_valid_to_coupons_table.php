<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsValidToCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('coupons', function (Blueprint $table) {
            $table->boolean('is_valid')->default(1)->after('status')->comment('生效状态：0-作废，1-生效');
        });
        Schema::table('coupon_rules', function (Blueprint $table) {
            $table->boolean('type')->default(4)->comment('类型，1-小时券，2-现金券，3-折扣券，4-全免券')->change();
            $table->boolean('is_active')->default(1)->after('value')
                ->comment('启用状态：0-停用，1-启用');
        });
        Schema::table('coupon_user_rules', function (Blueprint $table) {
            $table->boolean('is_active')->default(1)->after('is_new_user')
                ->comment('启用状态：0-停用，1-启用');
        });
        Schema::table('coupon_park_rules', function (Blueprint $table) {
            $table->string('district_id')->nullable()->after('city_id')->comment('区编码')->index();
            $table->boolean('is_active')->default(1)->after('park_property')
                ->comment('启用状态：0-停用，1-启用');
            $table->integer('park_property')->default(0)
                ->comment("停车场属性，0-全部，1-商业综合体，2-商业写字楼，3-商务酒店，4-公共场馆，5-医院，6-产业园，
                7-住宅，8-旅游景点，9-物流园，10-建材市场，11-学校，12-交通枢纽")->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('coupons', function (Blueprint $table) {
            $table->dropColumn('is_valid');
        });
        Schema::table('coupon_rules', function (Blueprint $table) {
            $table->string('type')->comment('类型')->change();
            $table->dropColumn('is_active');
        });
        Schema::table('coupon_user_rules', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });
        Schema::table('coupon_park_rules', function (Blueprint $table) {
            $table->string('park_property')->comment('停车场属性')->change();
            $table->dropColumn(['district_id', 'is_active']);
        });
    }
}
