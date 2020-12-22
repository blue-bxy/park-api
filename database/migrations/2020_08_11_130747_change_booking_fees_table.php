<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeBookingFeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('booking_fees', function (Blueprint $table) {
            $table->unsignedInteger('park_id')->after('id')->nullable()->comment('车场id');
            $table->dropColumn(['type','platform','park','owner']);
            $table->json('apt')->after('park_id')->comment('预约分成费率：platfotm-平台,park-车场,owner-业主,type[1-业主，2-物业]');
            $table->json('stop')->after('apt')->comment('停车分成费率：platfotm-平台,park-车场,owner-业主,type[1-业主，2-物业]');
            $table->unsignedInteger('status')->default(1)->after('stop')->comment('分成费率的状态，1-停用，2-启用');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('booking_fees', function (Blueprint $table) {
            $table->dropColumn('park_id');
            $table->dropColumn('apt');
            $table->dropColumn('stop');
            $table->dropColumn('status');
        });
    }
}
