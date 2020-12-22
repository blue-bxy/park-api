<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(\App\Models\Financial\Apply::class, function (Faker $faker) {
    return [
        'no'=>get_order_no(),
        'amount'=>$faker->numberBetween(1,1000),
        'payment_number'=>$faker->numberBetween(1,5),
        'business_type'=>$faker->numberBetween(1,2),
        'person_type'=>$faker->numberBetween(1,2),
        'apply_time'=>$faker->dateTimeBetween('2019-1-1',now()),
    ];
});
