<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParkCameraGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('park_camera_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('编组名称');
            $table->string('unique_id')->comment('唯一id');
            $table->integer('total_count')->comment('组内车位总数');
            $table->integer('available_count')->comment('组内可用车位数');
            $table->boolean('is_active')->default(1)->comment('启用状态，0-停用，1-启用');
            $table->foreignId('park_id')->comment('外键，关联parks表id');
            $table->foreignId('park_area_id')->comment('外键，关联park_area表id');
            $table->foreign('park_id')->on('parks')
                ->references('id')->onDelete('cascade');
            $table->foreign('park_area_id')->on('park_area')
                ->references('id')->onDelete('cascade');
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
        Schema::dropIfExists('park_camera_groups');
    }
}
