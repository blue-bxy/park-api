<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ParkSpaceAddCarportStatusAndPic extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('park_spaces', function (Blueprint $table) {
            $table->boolean('is_stop')->default(false)
                ->after('remark')
                ->comment('车位上是否有车');

            $table->string('pic')->nullable()
                ->after('is_stop')
                ->comment('车位照片');
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
            $table->dropColumn('is_stop', 'pic');
        });
    }
}
