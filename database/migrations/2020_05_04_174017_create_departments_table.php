<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDepartmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 部门表
        Schema::create('departments', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('guard_name');

            $table->timestamps();
        });
        // 用户部门关系
        Schema::create('model_has_departments', function (Blueprint $table) {
            $table->morphs('user');
            $table->foreignId('department_id');

            $table->foreign('department_id')
                ->on('departments')
                ->references('id');
        });

        // 职位表
        Schema::create('positions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('department_id')->nullable();
            $table->foreign('department_id')
                ->on('departments')
                ->references('id');

            $table->string('name');
            $table->string('guard_name');

            $table->timestamps();
        });

        // 用户部门职位关系
        Schema::create('model_has_department_positions', function (Blueprint $table) {
            $table->morphs('user');
            $table->foreignId('department_id');

            $table->foreign('department_id')
                ->on('departments')
                ->references('id');

            $table->foreignId('position_id');

            $table->foreign('position_id')
                ->on('positions')
                ->references('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('model_has_department_positions');
        Schema::dropIfExists('positions');
        Schema::dropIfExists('model_has_departments');
        Schema::dropIfExists('departments');
    }
}
