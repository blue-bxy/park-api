<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define(\App\Models\Users\UserParkingSpace::class, function (Faker $faker) {

    $park = \App\Models\Parks\Park::with('spaces')
        ->has('spaces')
        ->inRandomOrder()
        ->first();

    $space = $park->spaces->random()->first();

    return [
        'user_id' => \App\Models\User::query()->inRandomOrder()->first()->id,
        'park_id' => $park->id,
        'certificates' => [
            'https://www.zhifure.com/upload/images/2018/10/892933215.jpg',
            'https://www.zhifure.com/upload/images/2018/10/893118326.jpg'
        ],
        'contracts' => [],
        'number' => $space->number,
        'park_space_id' =>$space->id
    ];
});
