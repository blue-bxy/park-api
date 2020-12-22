<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CarRentsAddPic extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('car_rents', function (Blueprint $table) {
            $table->json('pics')->nullable()->comment('车位照片')->change()
                ->after('rent_num');

            $table->string('rent_start_time')->change();
            $table->string('rent_end_time')->change();

            $table->foreignId('user_space_id')->nullable()->after('park_space_id');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('car_rents', function (Blueprint $table) {
            $table->dropColumn('pics');
            $table->dropColumn('user_space_id');
        });
    }
}
