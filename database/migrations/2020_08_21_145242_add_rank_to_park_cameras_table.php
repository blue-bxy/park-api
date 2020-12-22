<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRankToParkCamerasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('park_cameras', function (Blueprint $table) {
            $table->integer('rank')->default(0)->after('remark')->comment('用于排序');
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
            $table->dropColumn('rank');
        });
    }
}
