<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use App\Models\Users\UserOrder;
use Illuminate\Http\Request;

$factory->define(UserOrder::class, function (Faker $faker) {

    $park = \App\Models\Parks\Park::query()->inRandomOrder()->first();
    $park_id = $park->id;

    $user = \App\Models\User::query()->inRandomOrder()->first();

    return [
        'order_no' => get_order_no(),

        'user_id'          => $user->id,
        //        'park_id'=>factory(\App\Models\Parks\Park::class)->create(),
        'park_id'          => $park_id,
         'coupon_id'        => factory(\App\Models\Coupons\Coupon::class)->create(),
//        'car_stop_id'      => factory(\App\Models\Dmanger\CarStop::class)->create(),
        'user_car_id'      => factory(\App\Models\Users\UserCar::class)->create([
            'user_id' => $user->id
        ]),
        'car_num' => '沪' . $faker->randomNumber(7, true),
        // 'car_apt_id'       => ,
        'subscribe_amount' => rand(10,20),
        'amount'           => 20,

        'discount_amount' => 0,
        'refund_amount'   => rand(1,5),
        'total_amount'    => 20,
        'payment_no'      => get_order_no(),
        'payment_gateway' => '支付宝',

        'status'       => 'pending',
        'order_type'=>$faker->numberBetween(1,2),
        'paid_at'      => $faker->date("Y-m-d H:i:s", 'now'),
        'cancelled_at' => $faker->date("Y-m-d H:i:s", 'now'),
        'refunded_at'  => $faker->date("Y-m-d H:i:s", 'now'),
        'finished_at'  => $faker->date("Y-m-d H:i:s", 'now'),
        'failed_at'    => $faker->date("Y-m-d H:i:s", 'now'),
        'commented_at' => $faker->date("Y-m-d H:i:s", 'now'),

    ];
});
