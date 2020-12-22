<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(\App\Models\Users\ParkingSpaceRentalBill::class, function (Faker $faker) {
    $body = ['出租收益', '提现'];

    $rand = rand(0, 1);

    return [
        'user_id' => \App\Models\User::query()->inRandomOrder()->first()->id,
        'no' => get_order_no(),
        'body' => $body[$rand],
        'type' => $rand,
        'amount' => rand(500, 3000),
        'order_type' => \App\Models\Users\UserOrder::class,
        'order_id' => \App\Models\Users\UserOrder::query()->inRandomOrder()->first()->id,
    ];
});
