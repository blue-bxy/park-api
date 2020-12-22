<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToParkSpacesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('park_spaces', function (Blueprint $table) {
            $table->string('unique_code')->comment('唯一编号')->after('number');
            $table->string('area_code')->nullable()->comment('区域编号')->after('unique_code');
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
            $table->dropColumn(['unique_code', 'area_code']);
        });
    }
}