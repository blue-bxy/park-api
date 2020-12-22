<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\Models\Users\UserConsumptionRecodes;
use Faker\Generator as Faker;

$factory->define(UserConsumptionRecodes::class, function (Faker $faker) {
	$status = ['pending','paid','cancelled','failed','refunded'];
    return [
        'serial_number'=>time().rand(1000,999),
        'car_number'=>'赣F'.rand(100000,999999),
        'park_name'=>'测试停车场',
        'amount'=>5 * rand(1,10),
        'payment_channel'=>rand(1,3),
        'payment_type'=>rand(1,2),
        'payment_account'=>$faker->phoneNumber,
        'channel_transaction_no'=>time().rand(1000,999),
        'business_type'=>rand(1,3),
        'status'=>$status[rand(0,4)],
        'user_type'=>'App\Models\User',
        'user_id'=>factory(\App\Models\User::class)->create(),
        'order_type'=>'App\Models\Users\UserOrder',
        'order_id'=>factory(\App\Models\Users\UserOrder::class)->create(),
        'created_at'=>$faker->date("Y-m-d H:i:s", 'now'),
//        'updated_at'=>$faker->date("Y-m-d H:i:s", 'now'),
    ];
});
