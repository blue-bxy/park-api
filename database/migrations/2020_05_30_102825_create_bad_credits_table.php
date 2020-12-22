<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBadCreditsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bad_credits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->nullable();
            $table->foreign('order_id')->on('user_orders')->references('id')->onDelete('cascade');
            $table->integer('bad_amount')->comment('坏账金额');
            $table->integer('already_amount')->nullable()->comment('已补金额');
            $table->integer('is_payment')->default(1)->comment('是否需补缴 1-否 2-是');
            $table->string('bad_results',30)->nullable()->comment('坏账结果');
            $table->string('bad_source',30)->comment('坏账来源');
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
        Schema::dropIfExists('bad_credits');
    }
}
