<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParkServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('park_services', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("park_id");
            $table->foreign('park_id')->on('parks')->references('id')->onDelete('cascade');
            $table->string('salesman_number',30)->comment("业务员工号");
            $table->string("sales_name",20)->comment("业务员姓名");
            $table->string("sales_phone",11)->comment("业务员联系电话");
            $table->string("contract_no",30)->comment("合同编号");
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
        Schema::dropIfExists('park_services');
    }
}
