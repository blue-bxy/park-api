<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Users\UserCar;
use Faker\Generator as Faker;
use Illuminate\Http\Request;

$factory->define(UserCar::class, function (Faker $faker) {
    return [
        'user_id'=>factory(\App\Models\User::class)->create(),
        'owner_name'=>$faker->name,
        'car_number'=>'赣F'.rand(100000,999999),
        'frame_number'=>'1G1BL52P7TR'.rand(100000,999999),
        'engine_number'=>'8V'.rand(65,105),
        'brand_model'=>'一汽大众',
        'face_license_imgurl'=>'img.jpg',
        'back_license_imgurl'=>'img.jpg',
        'is_default'=>0,
        'created_at'=>$faker->date("Y-m-d H:i:s", 'now'),
    ];
});
