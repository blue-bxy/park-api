<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyContractPeriodOnParkServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('park_services', function (Blueprint $table) {
            $table->timestamp('contract_start_period')->nullable()
                ->after('activation_code')->comment('合同开始日期');
            $table->timestamp('contract_end_period')->nullable()
                ->comment("合同结束日期")->after('contract_start_period');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('park_services', function (Blueprint $table) {
            $table->dropColumn(['contract_start_period', 'contract_end_period']);
        });
    }
}
