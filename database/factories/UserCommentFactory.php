<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\Models\Users\UserCollect;
use App\Models\Users\UserComment;
use Faker\Generator as Faker;

$factory->define(UserComment::class, function (Faker $faker) {
    return [
        'user_id'=>factory(\App\Models\User::class)->create(),
        'order_id'=>factory(\App\Models\Users\UserOrder::class)->create(),
        'content'=>"还行",
        'imgurl'=>'img.jpg',
        'rate'=>rand(1,5),
        'is_display'=>rand(0,1),
//        'created_at'=>$faker->date("Y-m-d H:i:s", 'now'),
    ];
});
