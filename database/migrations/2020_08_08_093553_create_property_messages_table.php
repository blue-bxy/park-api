<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePropertyMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('property_messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id')->comment('推送人员的id');
            $table->string('title')->nullable()->comment('标题');
            $table->string('content')->nullable()->comment('内容');
            $table->unsignedInteger('park_type')->nullable()->comment('车场类型');
            $table->unsignedInteger('park_property')->nullable()->comment('车场属性');
            $table->timestamps();
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
        Schema::dropIfExists('property_messages');
    }
}
