<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Coupons\Coupon;
use Faker\Generator as Faker;

$factory->define(Coupon::class, function (Faker $faker) {
    $rule =factory(\App\Models\Coupons\CouponRule::class)->create();
    $rule_id = $rule->id;
    $rule_type = $rule->type;
    $rule_value = $rule->value;

    return [
        'no' => get_order_no(),
        'title'=>$faker->title,
        'used_amount'=>1000,
        'need_integral_amount'=>100,
        'quota'=>20,
        'max_receive_num'=>5,
        'desc' => $faker->text,
        'park_id' => \App\Models\Parks\Park::query()->inRandomOrder()->first()->id,
        'start_time' => now(),
        'end_time' => now()->addMonth(),
        'expired_at' => now()->addMonths(2),
		'publisher_id' => 1,
		'publisher_type' => 'App\Models\Admin',
        'coupon_rule_id' => $rule_id,
        'coupon_park_rule_id' => factory(\App\Models\Coupons\CouponParkRule::class)->create(),
        'coupon_user_rule_id' => factory(\App\Models\Coupons\CouponUserRule::class)->create(),
        'coupon_rule_type' => $rule_type,
        'coupon_rule_value' => $rule_value,

    ];
});
