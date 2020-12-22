<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parks', function (Blueprint $table) {
            $table->id();
            $table->string('project_name',255)->comment("项目名称");
            $table->string('park_name',255)->comment("停车场名称");
            $table->string('park_number',8)->nullable()->comment("停车场编号");
            $table->foreignId('property_id')->comment('公司名称')->index();
            // $table->unsignedBigInteger('property_id')->comment("公司名称");
            // $table->foreign('property_id')->on('properties')->references('id')->onDelete('cascade');
            $table->foreignId('project_group_id')->nullable()->comment("集团名称")->index();
            // $table->unsignedBigInteger('project_group_id')->comment("集团名称");
            // $table->foreign('project_group_id')->on('project_groups')->references('id')->onDelete('cascade');


            $table->string('park_province',20)->comment("停车场所在省");
            $table->string('park_city',20)->comment("停车场所在市");
            $table->string('park_area',20)->comment("停车场所在区");
            $table->string('project_address',255)->comment("项目地址");
            // $table->geometryCollection('longitude_latitude')->nullable()->comment("经纬度");
            $table->string('longitude')->nullable()->comment("经度");
            $table->string('latitude')->nullable()->comment("纬度");
            $table->string('entrance_coordinate',100)->nullable()->comment("入口坐标");
            $table->string('exit_coordinate',100)->nullable()->comment("出口口坐标");
            $table->integer('park_type')->default(1)->comment("停车场类型:1室内，2室外，3室内+室外，4其他");
            $table->integer('park_cooperation_type')->default(1)->comment("停车场合作类型:1销售");
            $table->integer('park_client_type')->default(1)->comment("停车场客户端类型：1车牌识别");
            $table->integer('park_property')->default(1)->comment("停车场属性：1产业园");
            $table->integer('park_operation_state')->default(1)->comment("停车场运营状态：1待建，2运营");
            $table->integer('park_device_type')->default(1)->comment("停车场设备类型:");
            $table->integer('park_state')->default(1)->comment("车场状态：1启用   0停用");
            $table->string('park_height_permitted')->nullable()->comment("车场限高");
            $table->integer('score')->default(5)->comment("综合评分：1-5");

            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('parks');
    }
}
