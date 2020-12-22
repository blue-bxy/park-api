<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RoaGreenAddRobotExamine extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_comments', function (Blueprint $table) {
            $table->string('suggestion')->nullable()
                ->comment('审核建议：pass:正常，review:需要人工审核,block:文本违规');

            // normal：正常文本
            // spam：含垃圾信息
            // ad：广告
            // politics：涉政
            // terrorism：暴恐
            // abuse：辱骂
            // porn：色情
            // flood：灌水
            // contraband：违禁
            // meaningless：无意义
            // customized：自定义（例如命中自定义
            $table->string('label')->nullable()
                ->comment('文本垃圾检测结果的分类');

            $table->json('response')->nullable();
        });

        Schema::table('user_complaints', function (Blueprint $table) {
            $table->string('suggestion')->nullable()
                ->comment('审核建议：pass:正常，review:需要人工审核,block:文本违规');

            $table->string('label')->nullable()
                ->comment('文本垃圾检测结果的分类');

            $table->json('response')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_complaints', function (Blueprint $table) {
            $table->dropColumn('suggestion', 'label', 'response');
        });

        Schema::table('user_comments', function (Blueprint $table) {
            $table->dropColumn('suggestion', 'label', 'response');
        });
    }
}
