<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNumberToParkCamerasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('park_cameras', function (Blueprint $table) {
            $table->renameColumn('name', 'number');
        });
        Schema::table('park_cameras', function (Blueprint $table) {
            $table->string('number', 64)->comment('摄像头编号')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('park_cameras', function (Blueprint $table) {
            $table->renameColumn('number', 'name');
        });
        Schema::table('park_cameras', function (Blueprint $table) {
            $table->string('name', 50)->comment('摄像头名称')->change();
        });
    }
}
