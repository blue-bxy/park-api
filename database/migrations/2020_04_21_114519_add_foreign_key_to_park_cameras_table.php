<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeyToParkCamerasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('park_cameras', function (Blueprint $table) {
            $table->foreignId('park_area_id')->comment('外键，关联park_area表id');
            $table->foreign('park_area_id')->on('park_area')
                ->references('id')->onDelete('cascade');
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
            $table->dropForeign(['park_area_id']);
            $table->dropColumn('park_area_id');
        });
    }
}
