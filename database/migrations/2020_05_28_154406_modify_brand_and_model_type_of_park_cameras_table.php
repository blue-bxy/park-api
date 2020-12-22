<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyBrandAndModelTypeOfParkCamerasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('park_cameras', function (Blueprint $table) {
            $table->renameColumn('brand', 'brand_id');
            $table->renameColumn('model', 'brand_model_id');
        });
        Schema::table('park_cameras', function (Blueprint $table) {
            $table->foreignId('brand_id')->index()->change();
            $table->foreignId('brand_model_id')->index()->change();
            $table->boolean('monitor_type')->default(1)
                ->comment('监控类型，0-出入口，1-车位');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('park_cameras', function (Blueprint $table) {
            $table->dropIndex(['brand_id']);
            $table->dropIndex(['brand_model_id']);
            $table->dropColumn(['monitor_type']);
            $table->string('brand_id', 100)->nullable()->comment('品牌')->change();
            $table->string('brand_model_id', 100)->nullable()->comment('型号')->change();
        });
        Schema::table('park_cameras', function (Blueprint $table) {
            $table->renameColumn('brand_id', 'brand');
            $table->renameColumn('brand_model_id', 'model');
        });
    }
}
