<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Dmanger\CarStop;
use Faker\Generator as Faker;

$factory->define(CarStop::class, function (Faker $faker) {

    $park = \App\Models\Parks\Park::query()->inRandomOrder()->first();
    $park_id = $park->id;

    $user = \App\Models\User::query()->inRandomOrder()->first();
    $user_id = $user->id;

    $user_car = \App\Models\Users\UserCar::query()->inRandomOrder()->first();
    $user_car_id = $user_car->id;

    $park_space = \App\Models\Parks\ParkSpace::query()->inRandomOrder()->first();
    $park_space_id = $park_space->id;

    $user_order = \App\Models\Users\UserOrder::query()->inRandomOrder()->first();
    $user_order_id = $user_order->id;


    $img = [
            'https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1590238257410&di=3ce53932a5d2bc4e732584f089a692ea&imgtype=0&src=http%3A%2F%2Fcrawl.ws.126.net%2Fnbotreplaceimg%2F2bbabb8032f8e4a7e378338921da4987%2F1d868ba170268a6098ac0ebe0785cc77.jpg',
            'https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1590238409591&di=4a1e80848aa60b072724ca3b511c5290&imgtype=0&src=http%3A%2F%2Fp0.ifengimg.com%2Fpmop%2F2017%2F0726%2F1ED8A76BACCA4B201DBEA804D457760F5DA48FAD_size91_w931_h570.jpeg',
            'https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1590238456922&di=273d85e337df12a08399ab48164f2fbd&imgtype=0&src=http%3A%2F%2Fimg.ysoow.com%2Ftingchechang%2F2020%2F04%2F20200411qfn1586570008.jpg',
            'https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1590238550299&di=1b2d91ddb042f6999e0e74cc9890a2d0&imgtype=0&src=http%3A%2F%2Fimg1.gtimg.com%2Fyangzhou_house%2Fpics%2Fhv1%2F170%2F224%2F171%2F11176565.jpg',
            'https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1590238668806&di=853bf8d795719b62e9b77543a9db58c4&imgtype=0&src=http%3A%2F%2Fimages02.cdn86.net%2Fkps01%2FM00%2FE5%2F86%2FwKiAiVPZ3jCuPfv0AACEzWmK6AQ299.jpg',
            'https://ss0.bdstatic.com/70cFuHSh_Q1YnxGkpoWK1HF6hhy/it/u=1895282108,1103919494&fm=26&gp=0.jpg',
            'http://pics0.baidu.com/feed/eac4b74543a982262a1cdf482f470d074890ebc9.jpeg?token=ef8cd025163f634b9781c35663c3e9b0'
    ];
    return [
//        'user_id' => factory(\App\Models\User::class)->create(),
        'user_id' => $user_id,
        'user_car_id' => $user_car_id,
        'park_space_id' => $park_space_id,
        'user_order_id' => $user_order_id,
        'car_num' => '沪' . $faker->randomNumber(7, true),
        'park_id' =>$park_id,
//        'park_id' => factory(\App\Models\Parks\Park::class)->create(),
        'stop_price' => $faker->randomNumber(4, true),
        'free_price' => $faker->randomNumber(4, true),
        'free_type_id' => rand(1,2),
        'car_stop_type' => rand(1,3),
        'special_price' => $faker->randomNumber(4, true),
        'washed_price' => $faker->randomNumber(4, true),
        'stop_time'=> $faker->randomNumber(2, true),
        'car_in_img' => $img[rand(0,5)],
        'car_out_img' => $img[rand(0,5)],
        'car_in_time' => $faker->date("Y-m-d H:i:s", 'now'),
        'car_out_time' => $faker->date("Y-m-d H:i:s", 'now'),
        // 'stop_time' => $faker->randomNumber(2, true),
        'pay_stop_type' => '支付宝',
        'pay_stop_time' => $faker->date("Y-m-d H:i:s", 'now'),
//        'transaction_id' => $faker->creditCardNumber,
//        'stop_no' => $faker->creditCardNumber,
//
//        'paid_at'=> $faker->date("Y-m-d H:i:s", 'now'),
//        'cancelled_at'=> $faker->date("Y-m-d H:i:s", 'now'),
//        'refunded_at'=> $faker->date("Y-m-d H:i:s", 'now'),
//        'finished_at'=> $faker->date("Y-m-d H:i:s", 'now'),
//        'failed_at'=> $faker->date("Y-m-d H:i:s", 'now'),
//        'commented_at'=> $faker->date("Y-m-d H:i:s", 'now'),
    ];
});
