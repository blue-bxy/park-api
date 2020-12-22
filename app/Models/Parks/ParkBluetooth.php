<?php

namespace App\Models\Parks;

use Illuminate\Database\Eloquent\SoftDeletes;

class ParkBluetooth extends ParkDevice
{
    //蓝牙状态
    const STATUS_DISABLED = 0;  //停用
    const STATUS_ENABLED = 1;   //启用
    const STATUS_ERROR = 2;     //故障
    const STATUSES = [
        self::STATUS_DISABLED => '停用',
        self::STATUS_ENABLED => '启用',
        self::STATUS_ERROR => '故障'
    ];

    const NETWORK_STATUS_DISABLED = 0;  //停用
    const NETWORK_STATUS_ENABLED = 1;   //启用
    const NETWORK_STATUSES = [
        self::NETWORK_STATUS_DISABLED => '停用',
        self::NETWORK_STATUS_ENABLED => '启用'
    ];

    use SoftDeletes;

    protected $table = 'park_bluetooth';

    protected $fillable = ['number', 'brand_id', 'brand_model_id', 'ip', 'protocol',
        'gateway', 'electric', 'major', 'minor', 'uuid', 'status', 'network_status',
        'error', 'remark', 'park_id', 'park_area_id'];

}
