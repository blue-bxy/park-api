<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UserMessagesAddMessageId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_messages', function (Blueprint $table) {
            // $table->dropMorphs('source');

            $table->foreignId('message_id')->nullable();

            $table->foreign('message_id')->on('messages')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_messages', function (Blueprint $table) {
            // $table->morphs('source');

            $table->dropForeign(['message_id']);

            $table->dropColumn('message_id');
        });
    }
}
