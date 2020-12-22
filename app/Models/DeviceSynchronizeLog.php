<?php

namespace App\Models;

class DeviceSynchronizeLog extends EloquentModel
{
    //设备类型
    const TYPE_CAMERA = 1;      //摄像头
    const TYPE_LOCK = 2;        //地锁
    const TYPE_BLUETOOTH = 3;   //蓝牙

    protected $fillable = [
        'park_number', 'gateway', 'type', 'result'
    ];
}
