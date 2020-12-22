<?php

namespace App\Models\Parks;

use Illuminate\Database\Eloquent\SoftDeletes;

class ParkCamera extends ParkDevice
{
    //摄像头状态
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

    //监控类型
    const MONITOR_TYPE_ENTRANCE = 0;    //出入口摄像头
    const MONITOR_TYPE_SPACE = 1;       //车位摄像头
    const MONITOR_TYPES = [
        self::MONITOR_TYPE_ENTRANCE => '出入口摄像头',
        self::MONITOR_TYPE_SPACE => '车位摄像头'
    ];

    use SoftDeletes;

    protected $fillable = ['number', 'brand_id', 'brand_model_id', 'ip', 'protocol',
        'gateway', 'electric', 'status', 'network_status', 'error', 'remark', 'rank',
        'park_id', 'group_id', 'park_area_id', 'monitor_type'];

    public function virtualSpaces() {
        return $this->hasMany(ParkVirtualSpace::class);
    }
    public function spaces() {
        return $this->belongsToMany(ParkSpace::class, ParkVirtualSpace::class);
    }
}
