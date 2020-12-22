<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingFeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booking_fees', function (Blueprint $table) {
            $table->id();
            $table->integer('type')->comment('发布类型：1-车主用户 2-物业用户');
            $table->float('platform')->nullable()->comment('平台百分比');
            $table->float('park')->nullable()->comment('车场百分比');
            $table->float('owner')->nullable()->comment('车主百分比');
            $table->json('scope')->nullable()->comment('金额范围设置');
            $table->foreignId('user_id')->comment('设置人员');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('booking_fees');
    }
}
