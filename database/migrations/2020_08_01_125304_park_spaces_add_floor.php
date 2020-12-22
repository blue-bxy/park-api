<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ParkSpacesAddFloor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('park_spaces', function (Blueprint $table) {
            $table->integer('floor')->default(1)
                ->after('status')
                ->comment('冗余区域floor');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('park_spaces', function (Blueprint $table) {
            $table->dropColumn('floor');
        });
    }
}
