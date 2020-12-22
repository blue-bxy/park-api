<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(\App\Models\Financial\BadCredit::class, function (Faker $faker) {
    $order = \App\Models\Users\UserOrder::query()->inRandomOrder()->first();
    return [
        'order_id'=>$order->id,
        'bad_amount'=>$faker->numberBetween(1,500),
        'already_amount'=>$faker->numberBetween(1,600),
        'is_payment'=>$faker->numberBetween(1,2),
        'bad_results'=>'坏账结果',
        'bad_source'=>'坏账来源',
    ];
});
