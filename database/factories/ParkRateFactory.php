<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Parks\ParkRate;
use Faker\Generator as Faker;

$factory->define(ParkRate::class, function (Faker $faker) {
    return [
        'no' => str_random(),
        'name' => str_random(),
        'is_workday' => random_int(0, 1),
        'start_period' => 8,
        'end_period' => 22,
        'down_payments' => 10,
        'down_payments_time' => 30,
        'time_unit' => 30,
        'payments_per_unit' => 10,
        'first_day_limit_payments' => 100,
        'is_active' => 1,
        'parking_spaces_count' => 10,
        'type' => 0
    ];
});
