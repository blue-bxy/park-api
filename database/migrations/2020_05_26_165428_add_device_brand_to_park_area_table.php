<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeviceBrandToParkAreaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('park_area', function (Blueprint $table) {
            $table->boolean('can_publish_spaces')->default(1)
                ->comment('是否允许发布出租车位，0-禁止，1-允许');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('park_area', function (Blueprint $table) {
            $table->dropColumn('can_publish_spaces');
        });
    }
}
