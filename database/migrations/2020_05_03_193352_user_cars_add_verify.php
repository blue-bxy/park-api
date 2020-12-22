<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UserCarsAddVerify extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_cars', function (Blueprint $table) {
            $table->string('owner_name')->nullable()->change();
            $table->string('frame_number')->nullable()->change();
            $table->string('engine_number')->nullable()->change();

            $table->unique('car_number');

            $table->boolean('is_verify')->default(false)->after('is_default');
            $table->timestamp('verified_at')->nullable()->after('is_verify');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_cars', function (Blueprint $table) {
            $table->dropColumn('is_verify', 'verified_at');
        });
    }
}
