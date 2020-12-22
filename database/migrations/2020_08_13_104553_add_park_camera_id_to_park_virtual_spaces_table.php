<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddParkCameraIdToParkVirtualSpacesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('park_virtual_spaces', function (Blueprint $table) {
            $table->unsignedInteger('park_camera_id')->after('stop_id')->index()
                ->comment('外键，关联park_cameras表id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('park_virtual_spaces', function (Blueprint $table) {
            $table->dropColumn('park_camera_id');
        });
    }
}
