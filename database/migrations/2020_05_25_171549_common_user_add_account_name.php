<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CommonUserAddAccountName extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->string('account_name')->nullable()->comment('账号名称')->after('name');
        });

        Schema::table('properties', function (Blueprint $table) {
            $table->string('account_name')->nullable()->comment('账号名称')->after('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->dropColumn('account_name');
        });

        Schema::table('properties', function (Blueprint $table) {
            $table->dropColumn('account_name');
        });

    }
}
