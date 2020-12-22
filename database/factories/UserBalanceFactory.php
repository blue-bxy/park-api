<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(\App\Models\Users\UserBalance::class, function (Faker $faker) {
    $type=['charge' => '充值','subscribe' => '预约车位',
            'subscribe_renewal' => '预约车位续费','withdraw' => '提现',
            '1' => '普通退款','2' => '赔付退款'];
    $gateway = ['balance','wx_app','ali_app'];

    $k = array_rand($type,1);


    return [
        'user_id'=>factory(\App\Models\User::class)->create(),
        'order_no'=> get_order_no(),
        'trade_no'=>get_order_no(),
        'trade_type'=>rand(1,2),
        'amount'=>rand(1000,3000),
        'balance'=>rand(1000,9000),
        'fee'=>rand(100,900),
        'type'=>$k,
        'body'=>$type[$k],
        'gateway'=>$gateway[array_rand($gateway,1)],
        'status' => 1,
        'order_type'=>'App\Models\Dmanger\CarAptOrder',
        'order_id'=>factory(\App\Models\Dmanger\CarAptOrder::class)->create()->id,
        'created_at'=>$faker->date("Y-m-d H:i:s", 'now'),
        'updated_at'=>$faker->date("Y-m-d H:i:s", 'now'),
    ];
});
