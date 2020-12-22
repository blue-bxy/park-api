<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(App\Models\Financial\PlatformFinancialRecord::class, function (Faker $faker) {
    $body = ['day', 'month'];

    $rand = rand(0, 1);
    return [
        'platform'=>'领通云平台',
        'business'=>$faker->numberBetween(1,2),
        'type'=>$faker->numberBetween(1,2),
        'income'=>$faker->numberBetween(1,100000),
        'spending'=>$faker->numberBetween(1,100000),
        'balance'=>$faker->numberBetween(1,100000),
        'date'=>$faker->dateTimeBetween('2019-1-1',now()),
        'state'=>$body[$rand],
    ];
});
