<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\Models\Users\UserComplaint;
use Faker\Generator as Faker;

$factory->define(UserComplaint::class, function (Faker $faker) {
    return [
        'user_id'=>factory(\App\Models\User::class)->create(),
        'title'=>'占车位',
        'content'=>"这家伙又占我车位",
        'imgurl'=>'img.jpg',
        'type'=>rand(0,1),
        'result'=>rand(0,1),
        'urgencydegree'=>rand(0,3),
//        'created_at'=>$faker->date("Y-m-d H:i:s", 'now'),
//        'updated_at'=>$faker->date("Y-m-d H:i:s", 'now'),
    ];
});
