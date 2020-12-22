<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddQuantityToParkMapSpacesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('park_map_spaces', function (Blueprint $table) {
            $table->tinyInteger('quantity')->default(1)
                ->after('number')->comment('车位数');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('park_map_spaces', function (Blueprint $table) {
            $table->dropColumn('quantity');
        });
    }
}
