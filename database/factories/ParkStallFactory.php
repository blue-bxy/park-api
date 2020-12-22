<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Parks\ParkStall;
use Faker\Generator as Faker;

$factory->define(ParkStall::class, function (Faker $faker) {
//    $park = factory(\App\Models\Parks\Park::class)->create();
    return [
//        'park_id' => $park->id,
        'carport_count' => $faker->numberBetween(0,1000),
        'fixed_carport_count' => $faker->numberBetween(0,500),
        'charging_pile_carport' => $faker->numberBetween(0,500),
        'order_carport' => $faker->numberBetween(0,500),
        'temporary_carport_count' => $faker->numberBetween(0,100),
        'lanes_count' => $faker->numberBetween(0,80),
        'expect_temporary_parking_count' => $faker->numberBetween(0,100),
        'park_operation_time' => $faker->unixTime,
        'do_business_time' => $faker->unixTime,
        'fee_string' => $faker->randomFloat(2, 0, 10),
        'map_fee' => $faker->randomFloat(2, 0, 10),

    ];
});
