<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldToCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_comments', function (Blueprint $table) {
            $table->string('audit_status')->comment("审核状态：1待审核 2已通过 3未通过 4屏蔽 ");
            $table->string('auditor')->nullable()->comment("审核人员");
            $table->dateTime('audit_time')->nullable()->comment("审核时间");
            $table->string('refuse_reason')->nullable()->comment("驳回理由");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_comments', function (Blueprint $table) {
            $table->dropColumn(['audit_status','auditor','audit_time','refuse_reason']);
        });
    }
}
