<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Parks\ParkBluetooth;
use Faker\Generator as Faker;

$factory->define(ParkBluetooth::class, function (Faker $faker) {
    $model = \App\Models\BrandModel::query()
        ->whereHas('brand', function (\Illuminate\Database\Eloquent\Builder $query) {
            $query->where('type', '=', \App\Models\Brand::TYPE_BLUETOOTH);
        })->first();
    return [
        'number' => str_random(),
        'brand_id' => $model->brand_id,
        'brand_model_id' => $model->id,
        'ip' => '192.168.0.1',
        'protocol' => 'tcp',
        'gateway' => '255.255.255.0',
        'major' => mt_rand(0, 65535),
        'minor' => mt_rand(0, 65535),
        'uuid' => uuid(),
        'status' => random_int(0, 1),
        'network_status' => random_int(0, 1),
        'remark' => '测试',
    ];
});
