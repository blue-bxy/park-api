<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExportExcelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('export_excels', function (Blueprint $table) {
            $table->id();
            $table->string('excel_name',60)->default('')->comment('导出表的名称')->index('excel_name');
            $table->string('excel_type')->default('xls')->comment('导出表的类型，xls和xlsx格式')->index('excel_type');
            $table->string('excel_src',255)->default('')->comment('报表存放的路径');
            $table->unsignedBigInteger('excel_size')->default(0)->comment('报表文件的大小(kb)');
            $table->boolean('load_type_id')->default(false)->comment('是否下载');

            $table->timestamp('create_excel_time')->nullable()->comment('报表的创建时间')->index('create_excel_time');
            $table->timestamp('load_excel_time')->nullable()->comment('报表的导出时间')->index('out_excel_time');
            $table->timestamps();
            $table->softDeletes()->comment('软删除');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('export_excels');
    }
}
