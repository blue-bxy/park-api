<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateParkStallsSecondTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('park_stalls', function (Blueprint $table) {
            $table->renameColumn('longtime_carport_count','fixed_carport_count');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('park_stalls', function (Blueprint $table) {
            $table->renameColumn('fixed_carport_count','longtime_carport_count');
        });
    }
}
