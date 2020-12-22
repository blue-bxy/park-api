<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\Models\Users\UserCoupon;
use Faker\Generator as Faker;

$factory->define(UserCoupon::class, function (Faker $faker) {
    return [
        'user_id'=>factory(\App\Models\User::class)->create(),
        'coupon_id'=>factory(\App\Models\Coupons\Coupon::class)->create(),
        'use_time'=>now(),
        'expiration_time'=>now()->addMonths(2),
//        'created_at'=>$faker->date("Y-m-d H:i:s", 'now'),
//        'updated_at'=>$faker->date("Y-m-d H:i:s", 'now'),
    ];
});
