<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();

            $table->morphs('model');

            $table->string('type')->comment('审核内容类型:text,img');

            $table->string('value')->nullable()->comment('审核内容: 图片为url,文本为字符串');

            $table->string('suggestion')->nullable()
                ->comment('审核建议：pass:正常，review:需要人工审核,block:文本违规');
            $table->string('label')->nullable()->comment('文本垃圾检测结果的分类');
            $table->json('response')->nullable();

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
        Schema::dropIfExists('reviews');
    }
}
