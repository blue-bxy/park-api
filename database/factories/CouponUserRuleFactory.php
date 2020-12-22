<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define(\App\Models\Coupons\CouponUserRule::class, function (Faker $faker) {
    return [
        'title'=>$faker->title,
        'desc'=>$faker->text,
        'active_days'=>$faker->numberBetween(0,100),
        'low_frequency_days'=>$faker->numberBetween(0,100),
        'regression_days'=>$faker->numberBetween(0,100),
        'is_new_user'=>$faker->numberBetween(0,1),
        'user_type'=>'App\Models\Admin',
        'user_id'=>1,
    ];
});
