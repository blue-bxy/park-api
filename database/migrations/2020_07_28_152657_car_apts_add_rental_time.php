<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CarAptsAddRentalTime extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('car_apts', function (Blueprint $table) {
            $table->timestamp('renewed_at')->nullable()->comment('续费时间');
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
            $table->dropColumn('renewed_at');
        });
    }
}
