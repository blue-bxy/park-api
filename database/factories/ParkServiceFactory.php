<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define(\App\Models\Parks\ParkService::class, function (Faker $faker) {
//    $park = factory(\App\Models\Parks\Park::class)->create();
    return [
//        'park_id' => $park->id,
        'salesman_number' => $faker->numberBetween(0,10000),
        'sales_name' => $faker->name,
        'sales_phone' => $faker->phoneNumber,
        'contract_no' => $faker->numerify('E####'),
        'activation_code' => $faker->numerify('s####'),
        'contract_start_period' => $faker->dateTime,
        'contract_end_period' => $faker->dateTime
    ];
});
