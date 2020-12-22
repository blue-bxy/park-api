<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define(\App\Models\Parks\ParkSpace::class, function (Faker $faker) {
    $park_area=\App\Models\Parks\ParkArea::query()->inRandomOrder()->first();
    return [
        'park_area_id'=>$park_area->id,
        'number' => $park_area->code . get_sms_code(3),
        'unique_code' => str_random(32),
        'longitude' => $faker->longitude,
        'latitude' => $faker->latitude,
        'type' => random_int(1, 2),
        'category' => random_int(0, 1),
        'rent_type' => random_int(0, 1),
        'is_reserved_type' => random_int(0, 1),
        'status' => 0,
    ];
});
