<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CarAptOrdersAddIsRenewal extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('car_apt_orders', function (Blueprint $table) {
            $table->foreignId('user_id')->index()->after('id');

            $table->foreignId('user_order_id')->nullable()->index()->after('car_apt_id');
            $table->boolean('is_renewal')->default(false)->after('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('car_apt_orders', function (Blueprint $table) {
            $table->dropColumn('is_renewal', 'user_order_id', 'user_id');
        });
    }
}
