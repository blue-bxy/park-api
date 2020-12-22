<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGroupIdToParkCamerasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('park_cameras', function (Blueprint $table) {
            $table->foreignId('group_id')->default(0)->index()
                ->after('park_id')->comment('外键，关联park_camera_groups表id');
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
            $table->dropColumn('group_id');
        });
    }
}
