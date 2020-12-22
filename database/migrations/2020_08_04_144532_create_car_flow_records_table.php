<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarFlowRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('car_flow_records', function (Blueprint $table) {
            $table->id();
            $table->string('type')->default('in')->comment('出入类型：in:入，out:出');

            $table->foreignId('park_id')->nullable()->index();

            $table->string('code')->nullable()->index()->comment('停车场唯一值unique_code');

            $table->json('result')->nullable();

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
        Schema::dropIfExists('car_flow_records');
    }
}
