<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldToComplaintTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_complaints', function (Blueprint $table) {
            $table->string('order_no')->nullable()->comment("订单号");
            $table->string('handling_state')->comment("状态:0未处理 1已处理");
            $table->string('handling_person')->nullable()->comment("处理人员");
            $table->dateTime('handling_time')->comment("处理时间");
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
            $table->dropColumn(['order_no','handling_state','handling_person','handling_time']);
        });
    }
}
