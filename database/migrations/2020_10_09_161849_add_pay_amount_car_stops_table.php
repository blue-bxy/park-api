<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPayAmountCarStopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('car_stops', function (Blueprint $table) {
            $table->unsignedInteger('pay_amount')->after('stop_price')->comment('向app展示支付的费用');
            $table->unsignedInteger('discount_amount')->after('pay_amount')->comment('优惠费用');
            $table->unsignedInteger('deduct_amount')->after('discount_amount')->comment('实际支付停车费用');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('car_stops', function (Blueprint $table) {
            $table->dropColumn('pay_amount');
            $table->dropColumn('discount_amount');
            $table->dropColumn('deduct_amount');
        });
    }
}
