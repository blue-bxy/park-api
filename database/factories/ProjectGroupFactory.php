<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Customers\ProjectGroup;
use Faker\Generator as Faker;

$factory->define(ProjectGroup::class, function (Faker $faker) {
    return [
        'group_name' => $faker->company,
    ];
});
