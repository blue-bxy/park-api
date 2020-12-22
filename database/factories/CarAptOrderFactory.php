<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;
use App\Models\Dmanger\CarAptOrder;

$factory->define(CarAptOrder::class, function (Faker $faker) {

    $apt = \App\Models\Dmanger\CarApt::query()->inRandomOrder()->first();
    $apt_id = $apt->id;

    $coupon = \App\Models\Coupons\Coupon::query()->inRandomOrder()->first();
    return [
//        'car_apt_id' => factory(\App\Models\Dmanger\CarApt::class)->create(),
       'car_apt_id' => $apt_id,
        'transaction_id' => $faker->creditCardNumber,
        'no' => get_order_no(),
        'coupon_id' => $coupon->id,
        'payment_gateway' => '支付宝',
        // 'refund_no' => $faker->creditCardNumber,
        // 'refund_id' => $faker->creditCardNumber,
        'paid_at' => $faker->date("Y-m-d H:i:s", 'now'),
        'expired_at' => $faker->date("Y-m-d H:i:s", 'now'),
        'cancelled_at' => $faker->date("Y-m-d H:i:s", 'now'),
        'refunded_at' => $faker->date("Y-m-d H:i:s", 'now'),
        'finished_at' => $faker->date("Y-m-d H:i:s", 'now'),
        'failed_at' => $faker->date("Y-m-d H:i:s", 'now'),
        'commented_at' => $faker->date("Y-m-d H:i:s", 'now'),
        'amount' => $faker->randomNumber(4, true)
    ];
});
