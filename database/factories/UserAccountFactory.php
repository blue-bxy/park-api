<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(\App\Models\Users\UserAccount::class, function (Faker $faker) {
    $user = \App\Models\User::query()->inRandomOrder()->first();
    return [
        'user_id'=>$user->id,
        'account'=>$faker->phoneNumber,
    ];
});
