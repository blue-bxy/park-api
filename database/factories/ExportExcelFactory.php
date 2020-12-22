<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Dmanger\ExportExcel;
use Faker\Generator as Faker;

$factory->define(ExportExcel::class, function (Faker $faker) {
    return [
        'excel_name' => '测试报表名称' . rand(1,20),
        'excel_type' => 'Excel',
        'excel_src' => '2020/05/15/测试报表名称.Xlsx',
        'excel_size' => 20,
        'create_excel_time' => $faker->date("Y-m-d H:i:s", 'now'),
        'load_excel_time' => $faker->date("Y-m-d H:i:s", 'now'),
        'load_type_id' => rand(0,1),
    ];
});
