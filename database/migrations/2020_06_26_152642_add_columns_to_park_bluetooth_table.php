<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToParkBluetoothTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('park_bluetooth', function (Blueprint $table) {
            $table->integer('major')->comment('蓝牙的major')->after('gateway');
            $table->integer('minor')->comment('蓝牙的minor')->after('major');
            $table->char('uuid', 36)->after('minor');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('park_bluetooth', function (Blueprint $table) {
            $table->dropColumn(['major', 'minor', 'uuid']);
        });
    }
}
