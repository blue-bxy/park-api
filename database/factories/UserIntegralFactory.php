<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\Models\Users\UserIntegral;
use Faker\Generator as Faker;

$factory->define(UserIntegral::class, function (Faker $faker) {
    return [
        'user_id'=>factory(\App\Models\User::class)->create(),
        'operation'=>'操作',
        'integral_num'=>rand(1,100),
        'balance'=>rand(1,100),
        'order_type'=>'订单类型',
        'order_id'=>1,
//        'created_at'=>$faker->date("Y-m-d H:i:s", 'now'),
//        'updated_at'=>$faker->date("Y-m-d H:i:s", 'now'),
    ];
});
