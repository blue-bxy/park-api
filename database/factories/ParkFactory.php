<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Parks\Park;
use Faker\Generator as Faker;

$factory->define(Park::class, function (Faker $faker) {
    $regions = \App\Models\Regions\Province::query()
        ->inRandomOrder()
        ->with('city.country')
        ->first();

    $city = $regions->city->random()->first();

    $area = $city->country->random()->first();

    return [
        'project_name' => $faker->numerify('停车场项目 ###'),
        'park_name' => $faker->numerify('万达停车场 ###'),
        'park_number' => $faker->numerify('ESI####'),
        'property_id' => factory(\App\Models\Property::class)->create(),
        'project_group_id' => factory(App\Models\Customers\ProjectGroup::class)->create(),
        'park_province' => $regions->name,
        'park_city' => $city->name,
        'park_area' => $area->name,
        'project_address' => $faker->address,
        'longitude' => $faker->longitude,
        'latitude' => $faker->latitude,
        'entrance_coordinate' => $faker->longitude,
        'exit_coordinate' => $faker->longitude,
        'park_type' => $faker->numberBetween(1, 4),
        'park_cooperation_type' => 1,
        // 'park_client_type' => 1,
        'park_property' => $faker->numberBetween(1,3 ),
        'park_operation_state' => $faker->numberBetween(1, 2),

    ];
});
