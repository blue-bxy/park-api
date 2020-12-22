<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateParkServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('park_services', function (Blueprint $table) {
            $table->timestamp('contract_period')->nullable()
                ->comment("合同期限")->after('contract_no');
            $table->string('activation_code')->comment("激活码")->after('contract_no');
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
            $table->dropColumn(['contract_period','activation_code']);
        });
    }
}
