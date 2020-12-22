<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\Models\Users\UserPaymentLog;
use Faker\Generator as Faker;

$factory->define(UserPaymentLog::class, function (Faker $faker) {
    return [
        'user_id'=>factory(\App\Models\User::class)->create(),
        'order_no'=> $faker->randomNumber(7, true),
        'trade_no'=>get_order_no(),
        'buyer_account'=>123456,
        'arrival_account'=>45648941,
        'money_amount'=>rand(100,200),
        'request_info'=>'原始信息',
        'callback_info'=>'回调信息',
        'account_type'=>rand(1,3),
        'business_type'=>rand(0,4),
        'order_type'=>'App\Models\Dmanger\CarStop',
        'order_id'=>rand(1,5),
        'pay_type'=>rand(1,3),
        'created_at'=>$faker->date("Y-m-d H:i:s", 'now'),
        'updated_at'=>$faker->date("Y-m-d H:i:s", 'now'),
    ];
});
