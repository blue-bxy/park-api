<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeCommentAccountManagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('account_manages', function (Blueprint $table) {
            $table->string('account_area')->after('account_city')->comment('账号所在的区');
            $table->unsignedInteger('synchronization_type')->default(1)->comment('同步状态:1-未同步，2-已同步')->change();
            $table->unsignedInteger('audit_status')->default(1)->comment('审核状态:1-未审核，2-已审核')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('account_manages', function (Blueprint $table) {
            $table->dropColumn('account_area');
        });
    }
}
