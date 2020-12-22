<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCarNumToParkVirtualSpacesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('park_virtual_spaces', function (Blueprint $table) {
            $table->string('car_num')->nullable()->after('stop_id')
                ->comment('车牌号');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('park_virtual_spaces', function (Blueprint $table) {
            $table->dropColumn('car_num');
        });
    }
}
