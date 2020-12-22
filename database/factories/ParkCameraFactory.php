<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Parks\ParkCamera;
use Faker\Generator as Faker;

$factory->define(ParkCamera::class, function (Faker $faker) {
    $model = \App\Models\BrandModel::query()
        ->whereHas('brand', function (\Illuminate\Database\Eloquent\Builder $query) {
            $query->where('type', '=', \App\Models\Brand::TYPE_CAMERA);
        })->first();
    return [
        'number' => str_random(),
        'brand_id' => $model->brand_id,
        'brand_model_id' => $model->id,
        'ip' => '192.168.0.1',
        'protocol' => 'tcp',
        'gateway' => '255.255.255.0',
        'status' => random_int(0, 1),
        'network_status' => random_int(0, 1),
        'remark' => '测试',
    ];
});
