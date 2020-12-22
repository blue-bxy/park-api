<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CarRentsAddPaymentColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('car_rents', function (Blueprint $table) {
            $table->tinyInteger('is_workday')->default(2)
                ->comment('时间类型，0-非工作日，1-工作日，2-全部')->after('rent_price');

            $table->unsignedInteger('down_payments')->default(0)->after('rent_price')->comment('首付金额 单位分');
            $table->unsignedInteger('down_payments_time')->default(0)->after('down_payments')->comment('首付金额时长单位 分钟');
            $table->unsignedInteger('time_unit')
                ->default(60)->comment('出租时长单位')
                ->after('rent_price');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('car_rents', function (Blueprint $table) {
            $table->dropColumn('down_payments', 'down_payments_time', 'time_unit', 'is_workday');
        });
    }
}
