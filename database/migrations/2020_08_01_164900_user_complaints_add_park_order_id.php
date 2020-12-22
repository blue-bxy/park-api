<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UserComplaintsAddParkOrderId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_complaints', function (Blueprint $table) {
            $table->foreignId('order_id')->nullable()->after('user_id');

            $table->foreignId('park_id')->nullable()->after('order_id');

            $table->foreign('park_id')->on('parks')->references('id')->cascadeOnDelete();
            $table->foreign('order_id')->on('user_orders')->references('id')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_complaints', function (Blueprint $table) {
            $table->dropForeign(['order_id']);
            $table->dropForeign(['park_id']);

            $table->dropColumn('order_id', 'park_id');
        });
    }
}
