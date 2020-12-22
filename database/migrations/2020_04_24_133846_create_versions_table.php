<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVersionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('versions', function (Blueprint $table) {
            $table->id();
			$table->string('version_no')->comment('版本号');
            $table->string('update_description')->nullable()->comment('更新说明');
			$table->text('resource_url')->nullable()->comment('下载资源链接');
			$table->integer('is_force')->default(0)->comment('是否强制更新：0-不强制 1-强制');
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
        Schema::dropIfExists('versions');
    }
}
