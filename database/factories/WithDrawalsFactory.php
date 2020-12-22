<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define(\App\Models\Financial\Withdrawal::class, function (Faker $faker) {

    $park = \App\Models\Parks\Park::query()->inRandomOrder()->first();
    $park_id = $park->id;

    return [
        'withdrawal_no'   => get_order_no(),
        'person_type'     => $faker->numberBetween(1, 2),
        'apply_time'      => now(),
        'apply_money'     => $faker->numberBetween(1, 1000),
        // 'applicant' =>$faker->firstNameMale,
        'park_id'         => $park_id,
        // 'park_id' => factory(\App\Models\Parks\Park::class)->create(),
        'user_type'       => \App\Models\User::class,
        'user_id'         => \App\Models\User::inRandomOrder()->first()->id,
        'admin_id'        => \App\Models\Admin::query()->inRandomOrder()->first()->id,
        'audit_time'      => $faker->dateTime,
        'remark'          => $faker->text(100),
        'completion_time' => now()->addDay(),
        'status'          => $faker->numberBetween(1, 3),
        'account'         => $faker->numberBetween(1, 200),
    ];
});
