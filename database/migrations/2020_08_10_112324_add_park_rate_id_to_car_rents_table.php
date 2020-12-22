<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddParkRateIdToCarRentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('car_rents', function (Blueprint $table) {
            $table->foreignId('park_rate_id')->default(0)->index()
                ->after('park_space_id')->comment('外键，关联park_rates表id');
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
            $table->dropColumn('park_rate_id');
        });
    }
}
