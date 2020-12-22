<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNumberToParkBluetoothTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('park_bluetooth', function (Blueprint $table) {
            $table->renameColumn('name', 'number');
            $table->dropColumn('location');
        });
        Schema::table('park_bluetooth', function (Blueprint $table) {
            $table->string('number', 64)->comment('蓝牙编号')->change();
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
            $table->renameColumn('number', 'name');
            $table->string('location', 50)->comment('蓝牙位置');
        });
        Schema::table('park_bluetooth', function (Blueprint $table) {
            $table->string('name', 50)->comment('蓝牙名称')->change();

        });
    }
}
