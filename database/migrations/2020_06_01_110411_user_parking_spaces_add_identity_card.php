<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UserParkingSpacesAddIdentityCard extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_parking_spaces', function (Blueprint $table) {
            $table->string('id_card_number')->nullable()
                ->comment('身份证号码')
                ->after('number');
            $table->string('id_card_name')->nullable()
                ->comment('身份证姓名')
                ->after('id_card_number');
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
            $table->dropColumn('id_card_name', 'id_card_number');
        });
    }
}
