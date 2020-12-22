<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define(\App\Models\Coupons\CouponRule::class, function (Faker $faker) {
    return [
        'title'=>$faker->title,
        'desc'=>$faker->text,
        'type'=>$faker->numberBetween(1, 4),
        'value'=>$faker->numberBetween(1, 10),
        'user_type'=>'App\Models\Admin',
        'user_id'=>1,
    ];
});
