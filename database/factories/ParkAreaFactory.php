<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Parks\ParkArea;
use Faker\Generator as Faker;

$factory->define(ParkArea::class, function (Faker $faker) {
    $arr = ['A', 'B', 'C', 'D', 'E'];

    $code = $arr[array_rand($arr, 1)];

    return [
        'name' => $code.'区',
        'attribute' => random_int(1, 3),
        'code' => $code,
        'status' => 1,
        'car_model' => random_int(1, 4),
        'parking_places_count' => 100,
        'temp_parking_places_count' => 60,
        'fixed_parking_places_count' => 40,
        'charging_pile_parking_places_count' => 50,
        'garage_height_limit' => 220,
        'manufacturing_mode' => random_int(0, 3)
    ];
});
