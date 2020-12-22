<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\Models\Users\UserMessage;
use Faker\Generator as Faker;

$factory->define(UserMessage::class, function (Faker $faker) {
    return [
        'user_id'=>factory(\App\Models\User::class)->create(),
        'title'=>'各种消息',
        'content'=>'您有一张10元票',
        'imgurl'=>'http://img.jpg',
        'type'=>rand(0,3),
        'read_time'=>$faker->date("Y-m-d H:i:s", 'now'),
        'source_type'=>rand(0,3),
        'source_id'=>1,
//        'created_at'=>$faker->date("Y-m-d H:i:s", 'now'),
//        'updated_at'=>$faker->date("Y-m-d H:i:s", 'now'),
    ];
});
