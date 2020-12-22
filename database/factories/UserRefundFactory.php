<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\Models\Users\UserRefund;
use Faker\Generator as Faker;

$factory->define(UserRefund::class, function (Faker $faker) {
    return [
        'user_id'=>factory(\App\Models\User::class)->create(),
        'orders_type' => \App\Models\Dmanger\CarAptOrder::class,
        'orders_id'=>factory(\App\Models\Dmanger\CarAptOrder::class)->create(),
        'amount'=>$faker->numberBetween(1,500),
        'refunded_amount'=>$faker->numberBetween(1,500),
        'transfer_account'=>$faker->phoneNumber,
        'type'=>$faker->numberBetween(1,2),
        'refund_no'=>get_order_no(),
        'refund_id'=>get_order_no(),
        'reason'=>'因为我想退款',
        'remarks'=>'备注',
        // 'refund_way'=>$faker->numberBetween(1,2),
        // 'refund_channels'=>$faker->numberBetween(1,2),
        'refund_category'=>$faker->numberBetween(1,2),
        //'refunded_at'=>$faker->date("Y-m-d H:i:s", 'now'),
//        'failed_at'=>$faker->date("Y-m-d H:i:s", 'now'),
        'created_at'=>$faker->date("Y-m-d H:i:s", 'now'),
//        'updated_at'=>$faker->date("Y-m-d H:i:s", 'now'),
    ];
});
