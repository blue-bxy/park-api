<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UserParkingSpacesAddIdCarType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_parking_spaces', function (Blueprint $table) {
            $table->tinyInteger('id_card_type')->default(1)->after('number')->comment('证件类型:1身份证，2驾驶证，3护照');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_parking_spaces', function (Blueprint $table) {
            $table->dropColumn('id_card_type');
        });
    }
}
