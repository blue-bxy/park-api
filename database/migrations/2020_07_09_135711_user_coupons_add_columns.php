<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UserCouponsAddColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_coupons', function (Blueprint $table) {
            $table->string('title')->after('coupon_id');
            $table->foreignId('park_id')->nullable()->after('title');
            $table->unsignedInteger('amount')->after('park_id')->comment('面额');
            $table->unsignedInteger('use_min_amount')->default(0)->after('amount')
                ->comment('满xx减xx');

            //
            $table->timestamp('start_time')->nullable()->comment('发放开始时间');
            $table->timestamp('end_time')->nullable()->comment('发放结束时间');

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
            $table->dropColumn('title', 'park_id', 'amount', 'use_min_amount', 'start_time', 'end_time');
        });
    }
}
