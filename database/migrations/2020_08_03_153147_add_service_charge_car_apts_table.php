<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddServiceChargeCarAptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('car_apts', function (Blueprint $table) {
            $table->unsignedInteger('service_charge')->after('deduct_amount')->default(0)->comment('平台收取的手续费');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('car_apts', function (Blueprint $table) {
            $table->dropColumn('service_charge');
        });
    }
}
