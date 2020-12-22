<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(\App\Models\Financial\AccountManage::class, function (Faker $faker) {
    $park = \App\Models\Parks\Park::query()->inRandomOrder()->first();
    $property = \App\Models\Property::query()->inRandomOrder()->first();
    $province = \App\Models\Regions\Province::query()->inRandomOrder()->first();
    $city = \App\Models\Regions\City::query()->where('province_id',$province->province_id)->inRandomOrder()->first();
    $arr = ['工行上海分行','建行上海分行'];

    return [
        'park_id' => $park->id,
        'property_id' => $park->property_id,
        'account_name' => $faker->name,
        'account' => rand(1111111111,9999999999),
        'account_type' => rand(1,2),
        'account_province' => $province->name,
        'account_city' => $city->name,
        'bank_name' => $arr[rand(0,1)],
//        'sub_branch' => '虹桥支行',
        'contract_id' => rand(1111111,5555555),
        'synchronization_type' => rand(1,2),
        'audit_status' => rand(1,2)
    ];
});
