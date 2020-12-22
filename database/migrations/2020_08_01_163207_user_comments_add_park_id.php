<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UserCommentsAddParkId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_comments', function (Blueprint $table) {
            $table->foreignId('park_id')->nullable()->after('order_id');

            $table->foreign('park_id')->on('parks')->references('id')->cascadeOnDelete();

            $table->string('audit_status')->default(1)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_comments', function (Blueprint $table) {
            $table->dropForeign(['park_id']);

            $table->dropColumn('park_id');
        });
    }
}
