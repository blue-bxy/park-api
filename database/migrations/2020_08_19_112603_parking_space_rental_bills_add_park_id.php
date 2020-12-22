<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ParkingSpaceRentalBillsAddParkId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('parking_space_rental_bills', function (Blueprint $table) {
            $table->foreignId('park_id')->nullable()->after('user_id');

            $table->foreign('park_id')->on('parks')->references('id')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('parking_space_rental_bills', function (Blueprint $table) {
            $table->dropForeign(['park_id']);
            $table->dropColumn('park_id');
        });
    }
}
