<?php

namespace App\Models\Parks;

use Illuminate\Database\Eloquent\SoftDeletes;

class ParkSpaceLock extends ParkDevice
{
    //地锁状态
    const STATUS_DISABLED = 0;  //停用
    const STATUS_ENABLED = 1;   //启用
    const STATUS_ERROR = 2;     //故障
    const STATUSES = [
        self::STATUS_DISABLED => '停用',
        self::STATUS_ENABLED => '启用',
        self::STATUS_ERROR => '故障'
    ];

    use SoftDeletes;

    protected $fillable = [
        'number', 'brand_id', 'brand_model_id', 'ip', 'protocol', 'gateway',
        'electric', 'status', 'network_status', 'error', 'remark', 'park_id',
        'park_area_id', 'park_space_id'
    ];

    public function space()
    {
        return $this->belongsTo(ParkSpace::class, 'park_space_id');
    }
}
