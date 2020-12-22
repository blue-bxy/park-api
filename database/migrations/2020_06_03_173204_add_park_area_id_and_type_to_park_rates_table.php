<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddParkAreaIdAndTypeToParkRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('park_rates', function (Blueprint $table) {
            $table->foreignId('park_area_id')->index();
            $table->boolean('type')->default(0)
                ->comment('费率类型：0-车场，1-区域，2-车位');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('park_rates', function (Blueprint $table) {
            $table->dropColumn(['park_area_id', 'type']);
        });
    }
}
