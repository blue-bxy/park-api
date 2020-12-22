<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Dmanger\CarApt;
use Faker\Generator as Faker;

$factory->define(CarApt::class, function (Faker $faker) {
    $user = factory(\App\Models\User::class)->create();

    $park = \App\Models\Parks\Park::query()->inRandomOrder()->first();
    $park_id = $park->id;

    $user_order = \App\Models\Users\UserOrder::query()->inRandomOrder()->first();
    $user_order_id = $user_order->id;

    $user_car =\App\Models\Users\UserCar::query()->inRandomOrder()->first();
    $user_car_id = $user_car->id;

    $park_space = \App\Models\Parks\ParkSpace::query()->inRandomOrder()->first();
    $park_space_id = $park_space->id;

    return [
        'user_id' => $user->id,
        'user_order_id' => $user_order_id,
//        'phone' => $faker->phoneNumber,
//        'park_id' => factory(\App\Models\Parks\Park::class)->create(),
        'park_id' =>$park_id,
//        'car_num' => '沪' . $faker->randomNumber(7, true),
        'park_space_id' => $park_space_id,
        'user_car_id' => $user_car_id,
        'amount' => rand(1000,2000),
        'total_amount' => rand(2000,4000),
        'deduct_amount' => rand(1000,2000),
        'refund_total_amount' => rand(1000,2000),
        'car_rent_id' => factory(\App\Models\Dmanger\CarRent::class)->create()->id,
        'car_stop_id'=> factory(\App\Models\Dmanger\CarStop::class)->create()->id,
//        'car_stop_num' => 'A210',
//        'car_stop_type' => rand(1,3),
//        'apt_price' => $faker->randomNumber(5, true),
        'apt_start_time' => $faker->date("Y-m-d H:i:s", 'now'),
        'apt_end_time' => $faker->date("Y-m-d H:i:s", 'now'),
        'apt_time' => $faker->randomNumber(2, true),
    ];
});
