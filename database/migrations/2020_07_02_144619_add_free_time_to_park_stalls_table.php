<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFreeTimeToParkStallsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('park_stalls', function (Blueprint $table) {
            $table->unsignedInteger('free_time')->default(0)
                ->after('lanes_count')->comment('免费时长（分钟）');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('park_stalls', function (Blueprint $table) {
            $table->dropColumn('free_time');
        });
    }
}
