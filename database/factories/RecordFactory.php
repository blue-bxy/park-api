<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define(App\Models\Financial\Record::class, function (Faker $faker) {
    $withdrawal = \App\Models\Financial\Withdrawal::query()->inRandomOrder()->first();
    return [
        'record_no'=>get_order_no(),
        'withdrawal_id'=>$withdrawal->id,
        'park_fee'=>$faker->numberBetween(1,500),
        'adjust_amount'=>$faker->numberBetween(1,800),
        'adjust_type'=>$faker->numberBetween(1,2),
        'reason'=>'调整原因',
        'is_loss'=>$faker->numberBetween(1,2),
        'operator'=>$faker->name,
    ];
});
