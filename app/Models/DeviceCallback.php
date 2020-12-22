<?php

namespace App\Models;


class DeviceCallback extends EloquentModel
{
    protected $guarded = ['id'];

    protected $casts = [
        'result' => 'array'
    ];
}
