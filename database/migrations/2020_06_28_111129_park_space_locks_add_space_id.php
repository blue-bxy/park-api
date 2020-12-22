<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ParkSpaceLocksAddSpaceId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('park_space_locks', function (Blueprint $table) {
            $table->foreignId('park_space_id')->index()->nullable()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('park_space_locks', function (Blueprint $table) {
            $table->dropColumn('park_space_id');
        });
    }
}
