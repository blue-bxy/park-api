<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CarStopsUserIdChange extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('car_stops', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->change();
            $table->foreignId('user_car_id')->nullable()->change();
            $table->foreignId('user_order_id')->nullable()->change();
            $table->foreignId('park_space_id')->nullable()->change();

            $table->string('sold_id')->nullable()->index()->after('park_space_id')
                ->comment('推送方的唯一id');

            $table->index(['user_id', 'user_car_id', 'user_order_id', 'park_space_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('car_stops', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable(false)->change();
            $table->foreignId('user_car_id')->nullable(false)->change();
            $table->foreignId('user_order_id')->nullable(false)->change();
            $table->foreignId('park_space_id')->nullable(false)->change();

            $table->dropColumn('sold_id');

            $table->dropIndex(['user_id', 'user_car_id', 'user_order_id', 'park_space_id']);
        });
    }
}
