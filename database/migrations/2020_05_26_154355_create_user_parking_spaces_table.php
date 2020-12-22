<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserParkingSpacesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_parking_spaces', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->index();
            // 小区
            $table->foreignId('park_id')->index();
            //车位产权证
            $table->json('certificates');
            // 租赁合同
            $table->json('contracts')->nullable();

            $table->string('number')->comment('车位编号');

            $table->foreignId('park_space_id')->nullable()->index();

            $table->string('status')->default('pending')->comment('申请中、已审核、未通过');

            $table->string('remark')->nullable();

            $table->foreignId('property_id')->nullable()->index(); // 审核人
            $table->foreignId('admin_id')->nullable()->index(); // 审核人
            // 需要通过物业端，云端授权
            $table->timestamp('allowed_at')->nullable();
            // 用户操作 开启或关闭
            $table->timestamp('opened_at')->nullable();

            $table->timestamp('finished_at')->nullable();
            $table->timestamp('failed_at')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->unsignedInteger('rental_amount')->default(0)->comment('车位出租收益')
                ->after('balance');

            $table->json('cache')->nullable()->after('rental_amount');
        });

        // 出租记录
        Schema::create('parking_space_rental_records', function (Blueprint $table) {
            $table->id();

            $table->foreignId('rental_user_id')->index(); // 租车用户
            $table->foreignId('user_id')->index(); // 出租用户
            // 租金单价、总金额、状态（进行中、已完成）、出租时间、已租时长、
            $table->foreignId('car_rent_id')->index();

            $table->foreignId('user_car_id')->nullable()->comment('已租车位用户车辆表的id');

            $table->unsignedInteger('rent_time')->comment('已租时长');

            $table->unsignedInteger('amount')->comment('总金额');

            $table->unsignedInteger('fee')->comment('手续费');

            $table->timestamp('finished_at')->nullable()->comment('完成时间');

            $table->softDeletes();
            $table->timestamps();
        });


        // 车位出租账单
        Schema::create('parking_space_rental_bills', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->index();

            $table->string('no'); // 交易号，由order获得

            $table->string('body'); // 车位租金、提现

            $table->unsignedInteger('amount');

            $table->unsignedInteger('fee')->default(0)->comment('手续费');

            $table->unsignedInteger('rental_amount')->comment('更新后的金额');

            $table->boolean('type')->default(false)->comment('增加，减少');

            $table->morphs('order');

            $table->softDeletes();
            $table->timestamps();
        });

        // 车位出租
        // Schema::create('parking_space_rentals', function (Blueprint $table) {
        //     $table->id();
        //
        //     $table->string('no');
        //
        //     $table->integer('type')->comment('出租车位的类型，1表示物业出租，2表示业主出租');
        //
        //     $table->morphs('user');
        //
        //     $table->foreignId('park_id')->index();
        //
        //     $table->foreignId('park_area_id')->nullable()->index();
        //
        //     $table->foreignId('park_space_id')->nullable()->index();
        //
        //     $table->string('number')->comment('车位编号');
        //
        //     $table->json('pics')->nullable()->comment('图片');
        //
        //     $table->unsignedInteger('amount')->comment('出租金额');
        //     $table->string('start_time')->comment('开始时间');
        //     $table->string('end_time')->comment('结束时间');
        //
        //     $table->boolean('is_active')->default(false);
        //
        //     $table->unsignedInteger('total_time')->default(0)->comment('出租总时长');
        //     $table->unsignedInteger('total_amount')->default(0)->comment('出租总金额');
        //
        //     $table->softDeletes();
        //     $table->timestamps();
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('parking_space_rental_records');

        Schema::dropIfExists('parking_space_rental_bills');

        Schema::dropIfExists('user_parking_spaces');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('rental_amount', 'cache');
        });
    }
}
