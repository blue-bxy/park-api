<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use \App\Models\Financial\SettleOrder;
use Faker\Generator as Faker;

$factory->define(SettleOrder::class, function (Faker $faker) {
    return [
        'order_no'=>$faker->creditCardNumber,
        'license_plate_number'=>$faker->buildingNumber,
        'park_id'=>rand(1,4),
        'playing_time'=>$faker->dateTime,
        'stop_time'=>rand(1,20),
        'parking'=>rand(10,100),
        'electronic_payment'=>rand(1,100),
        'settlement_date'=>$faker->dateTime,
    ];
});
