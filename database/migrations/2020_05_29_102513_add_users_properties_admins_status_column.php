<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUsersPropertiesAdminsStatusColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // 冻结用户 提现功能
            $table->timestamp('banned_withdraw')->nullable()->comment('冻结提现')
                ->after('verified_at');

            $table->timestamp('banned_login')->nullable()->after('banned_withdraw');
        });

        Schema::table('properties', function (Blueprint $table) {
            // 冻结用户 提现功能
            $table->timestamp('banned_withdraw')->nullable()
                ->comment('冻结提现')
                ->after('password');
            $table->timestamp('banned_login')->nullable()->after('banned_withdraw');
        });

        Schema::table('admins', function (Blueprint $table) {
            $table->timestamp('banned_login')->nullable()->after('password');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('banned_login');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('banned_withdraw', 'banned_login');
        });

        Schema::table('properties', function (Blueprint $table) {
            $table->dropColumn('banned_withdraw', 'banned_login');
        });
    }
}
