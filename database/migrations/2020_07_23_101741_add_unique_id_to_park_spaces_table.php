<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUniqueIdToParkSpacesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('park_spaces', function (Blueprint $table) {
            $table->renameColumn('unique_code', 'map_unique_id');
            $table->string('device_unique_id')->nullable()
                ->comment('设备方提供的唯一编号')->after('number');
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
            $table->dropColumn('device_unique_id');
            $table->renameColumn('map_unique_id', 'unique_code');
        });
    }
}
