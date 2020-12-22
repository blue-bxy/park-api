<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(App\Models\Withdrawal\AptOrderDay::class, function (Faker $faker) {
    $parks=App\Models\Parks\Park::query()->inRandomOrder()->first();
    return [
        'park_id'=>$parks->id,
        'no'=>get_order_no(),
        'type'=>rand(1,3),
        'amount'=>$faker->numberBetween(1,10000),
        'time'=>$faker->dateTimeBetween('2020-6-1',now())
    ];
});
