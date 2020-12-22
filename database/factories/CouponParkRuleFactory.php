<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define(\App\Models\Coupons\CouponParkRule::class, function (Faker $faker) {
    return [
        'title'=>$faker->title,
        'desc'=>$faker->text,
        'turnover_rate'=>$faker->randomFloat(2, 0, 1),
        'province_id'=>array_rand([110000000000,310000000000,120000000000]),
        'city_id'=>110100000000,
        'cooperate_days'=>$faker->numberBetween(1, 100),
        'park_property'=>$faker->numberBetween(1,3),
        'user_type'=>'App\Models\Admin',
        'user_id'=>1,
    ];
});
