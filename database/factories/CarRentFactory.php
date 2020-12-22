<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(\App\Models\Dmanger\CarRent::class, function (Faker $faker) {

    $parkSpace = \App\Models\Parks\ParkSpace::query()->inRandomOrder()->first();
    $park_space_id = $parkSpace->id;

    $park = \App\Models\Parks\Park::query()->inRandomOrder()->first();
    $park_id = $park->id;

    return [
        'user_id' => \App\Models\User::query()->inRandomOrder()->first()->id,
        //        'park_id' => factory(\App\Models\Parks\Park::class)->create(),
        'park_id' => $park_id,
        'rent_num' => 'A区'.$faker->randomNumber(3, true),
        'rent_price' => $faker->randomNumber(4, true),
        'rent_start_time' => $faker->date("Y-m-d H:i:s", 'now'),
        'rent_end_time' => $faker->date("Y-m-d H:i:s", 'now'),
        'rent_status' => rand(0,1),
        'rent_no' => get_order_no(),
        'rent_type_id' => rand(1,3),
        'rent_time' => $faker->randomNumber(2, true),
        'rent_all_price' => $faker->randomNumber(4, true),
        'start' => '00:00',
        'stop' => '23:59',
        'park_space_id' => $park_space_id,
        'car_num' => '沪' . $faker->randomNumber(7, true),
    ];
});
