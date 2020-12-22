<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Recharge;
use Faker\Generator as Faker;

$factory->define(Recharge::class, function (Faker $faker) {
    return [
        'user_id' => factory(\App\Models\User::class)->create(),
        'no'=>get_order_no(),
        'amount'=>$faker->numberBetween(1, 100),
        'paid_amount'=>$faker->numberBetween(1,200),
        'transaction_id'=>get_order_no(),
        'gateway'=>'支付宝',
        'refund_no'=>get_order_no(),
        'refund_id'=>get_order_no(),
        'refund_amount'=>$faker->numberBetween(1,500),
        'refunded_at'=>$faker->dateTimeBetween('2019-01-01', 'now', 'PRC'),
        'status'=>'paid',
        'paid_at'=>$faker->dateTimeBetween('2019-01-01', 'now', 'PRC'),
        'expired_at'=>$faker->dateTimeBetween('2019-01-01', 'now', 'PRC'),
        'failed_at'=>$faker->dateTimeBetween('2019-01-01', 'now', 'PRC'),
    ];
});
