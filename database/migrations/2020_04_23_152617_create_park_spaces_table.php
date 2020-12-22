<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParkSpacesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('park_spaces', function (Blueprint $table) {
            $table->id();

            $table->foreignId('park_area_id');
            $table->foreign('park_area_id')->on('park_area')
                ->references('id')->onDelete('cascade');

            $table->string('number')->comment('车位编号');

            $table->boolean('type')->default(0)
                ->comment('车位类型，0-临停+固定，1-固定，2-临停');

            $table->boolean('category')->default(0)
                ->comment('车位类别: 0小轿车，1充电桩车位，2代步车，3长厢车');

            $table->integer('rent_type')
                ->comment('出租类型，0-临时，1-长租，2-不可出租');

            $table->boolean('is_reserved_type')
                ->comment('是否可以预约，0-不能预约，1-可以预约');

            $table->integer('status')->default(0)
                ->comment('车位状态，0-未发布，1-已发布，2-停用，3-预约中,
                4-已预约，5-已停车');

            $table->string('remark')->nullable()->comment('备注');

            $table->softDeletes();
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
        Schema::dropIfExists('park_spaces');
    }
}
