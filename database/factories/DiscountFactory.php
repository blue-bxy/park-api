<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Financial\Discount;
use Faker\Generator as Faker;

$factory->define(Discount::class, function (Faker $faker) {
    return [
//        'project_name' => $faker->text(20),
        'discount_coupon' => $faker->text(20),
//        'discount_type' => $faker->numberBetween(1,4),
//        'rule_hour' => $faker->numberBetween(1,10),
//        'rule_money' => $faker->numberBetween(1,10),
//        'rule_discount' => $faker->numberBetween(1,9),
        'merchant_type' => $faker->numberBetween(1,2),
        'select_profile_type' => $faker->numberBetween(1,3),
        'issue_form' => $faker->numberBetween(1,2),
        'user_issue_count' => $faker->numberBetween(1,5),
        'issue_start_date' => $faker->date(),
        'issue_end_date' => $faker->date(),
//        'discount_coupon_number' => $faker->numberBetween(1,200),
//        'use_state' => $faker->numberBetween(1,2),
    ];
});
