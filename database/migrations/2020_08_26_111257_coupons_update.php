<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CouponsUpdate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('coupon_rules', function (Blueprint $table) {
            $table->unsignedInteger('amount')->after('title');

            $table->unsignedInteger('use_scene')->default(1)
                ->comment('使用场景:1通用，2预约费，3停车费')->after('amount');
        });

        Schema::table('coupons', function (Blueprint $table) {
            $table->unsignedInteger('use_scene')
                ->default(1)
                ->comment('使用场景:1通用，2预约费，3停车费')
                ->after('status');

            // $table->string('type');
            /*$rules = [
                // 规则
                'rule' => [
                    'use_scene' => 1, // 通用
                    'type' => 3, // 折扣
                    'value' => 85 // 85折
                ],
                // 停车场属性
                'park' => [

                ],
                // 用户属性
                'user' => [

                ]
            ];*/

            $table->longText('qrcode_data')->nullable()->comment('二维码数据');

            $table->json('assign_user')->nullable()->comment('指定发放用户');

            $table->json('rules')->nullable()->comment('规则集合：{折扣规则，停车场属性，用户属性}');

            $table->timestamp('valid_start_time')->nullable()->comment('生效开始时间')
                ->after('end_time');
            $table->timestamp('valid_end_time')->nullable()->comment('生效结束时间')
                ->after('valid_start_time');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('coupon_rules', function (Blueprint $table) {
            $table->dropColumn('amount', 'use_scene');
        });

        Schema::table('coupons', function (Blueprint $table) {
            $table->dropColumn('use_scene', 'rules', 'valid_start_time', 'valid_end_time', 'assign_user', 'qrcode_data');
        });
    }
}
