<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParkRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('park_rates', function (Blueprint $table) {
            $table->id();

            $table->foreignId("park_id");
            $table->foreign('park_id')
                ->on('parks')
                ->references('id')
                ->onDelete('cascade');

            $table->string('no')->comment('编号');
            $table->string('name')->comment('费率名称');
            $table->unsignedInteger('is_workday')->comment('时间类型，0-非工作日，1-工作日，2-全部');
            $table->unsignedInteger('start_period')->comment('（每天）开始时间段');
            $table->unsignedInteger('end_period')->comment('（每天）结束时间段');
            $table->unsignedInteger('down_payments')->comment('首付金额（分）');
            $table->unsignedInteger('down_payments_time')->comment('首付时长（分钟）');
            $table->unsignedInteger('time_unit')->comment('单位时长（分钟）');
            $table->unsignedInteger('payments_per_unit')->comment('单位金额（分）');
            $table->unsignedInteger('first_day_limit_payments')->comment('24小时限额（分）');
            $table->boolean('is_active')->default(1)->comment('启用状态，0-停用，1-启用');

            $table->unsignedInteger('parking_spaces_count')->comment('车位数');
            $table->morphs('publisher');

            $table->softDeletes();
            $table->timestamps();

        });

        // 停车场车位、停车场、费率关系
        Schema::create('model_has_park_rates', function (Blueprint $table) {
            $table->morphs('model');
            $table->foreignId('park_rate_id');
            $table->foreign('park_rate_id')->on('park_rates')
                ->references('id')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('model_has_park_rates');
        Schema::dropIfExists('park_rates');
    }
}
