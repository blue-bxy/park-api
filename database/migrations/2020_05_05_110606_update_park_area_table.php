<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateParkAreaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('park_area', function (Blueprint $table) {
            $table->renameColumn('long_term_parking_places_count', 'fixed_parking_places_count');
            $table->dropColumn(['reserved_parking_places_count', 'attribute']);
        });
        Schema::table('park_area', function (Blueprint $table) {
            $table->integer('attribute')
                ->comment('区域属性，1-临停，2-固定，3-固定+临停');
            $table->boolean('status')->comment('区域状态，1-启用，0-停用');
            $table->integer('car_model')
                ->comment('车型，1-小型车，2-中大型车，3-大型车，4-超大型车');
            $table->unsignedInteger('parking_places_count')->default(0)
                ->comment('车位总数')->change();
            $table->unsignedInteger('fixed_parking_places_count')->default(0)
                ->comment('固定车位数')->change();
            $table->unsignedInteger('temp_parking_places_count')->default(0)
                ->comment('临时车位数')->change();
            $table->unsignedInteger('charging_pile_parking_places_count')->default(0)
                ->comment('充电桩车位数');
            $table->integer('garage_height_limit')->comment('车库限高（cm)');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('park_area', function (Blueprint $table) {
            $table->integer('temp_parking_places_count')->default(0)
                ->comment('临时车位数')->change();
            $table->integer('fixed_parking_places_count')->default(0)
                ->comment('长租车位数')->change();
            $table->integer('parking_places_count')->default(0)
                ->comment('车位总数')->change();
            $table->dropColumn(['garage_height_limit',
                'charging_pile_parking_places_count',
                'car_model', 'status', 'attribute']);
        });
        Schema::table('park_area', function (Blueprint $table) {
            $table->integer('reserved_parking_places_count')->default(0)
                ->comment('预约车位数');
            $table->string('attribute', 50)->comment('区域属性');
            $table->renameColumn('fixed_parking_places_count', 'long_term_parking_places_count');
        });
    }
}
