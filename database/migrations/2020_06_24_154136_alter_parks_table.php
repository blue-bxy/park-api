<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterParksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('parks', function (Blueprint $table) {
            $table->string('company')->nullable()
                ->after('park_number')->comment('公司');
            $table->integer('park_cooperation_type')->default(1)
                ->comment("停车场合作类型，0-免费，1-销售")->change();
            $table->integer('park_property')->default(1)
                ->comment("停车场属性，1-商业综合体，2-商业写字楼，3-商务酒店，4-公共场馆，5-医院，6-产业园，
                7-住宅，8-旅游景点，9-物流园，10-建材市场，11-学校，12-交通枢纽")->change();
            $table->integer('park_operation_state')->default(1)
                ->comment("停车场运营状态，1-运营，2-施工，3-异常运营，4-账户取消，5-取消运营，6-拆除")
                ->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('parks', function (Blueprint $table) {
            $table->dropColumn('company');
            $table->integer('park_cooperation_type')->default(1)
                ->comment("停车场合作类型:1销售")->change();
            $table->integer('park_property')->default(1)
                ->comment("停车场属性：1产业园")->change();
            $table->integer('park_operation_state')->default(1)
                ->comment("停车场运营状态：1待建，2运营")->change();
        });
    }
}
