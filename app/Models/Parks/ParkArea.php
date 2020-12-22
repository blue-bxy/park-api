<?php

namespace App\Models\Parks;

use App\Models\Brand;
use App\Models\EloquentModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class ParkArea extends EloquentModel
{
    //区域属性
    const ATTRIBUTE_All = 3;    //固定+临停
    const ATTRIBUTE_FIXED = 2;  //固定
    const ATTRIBUTE_TEMP = 1;   //临停

    //区域状态
    const STATUS_ENABLED = 1;     //启用
    const STATUS_DISABLED = 0;  //停用

    //发布出租车位
    const CAN_PUBLISH_SPACES_ON = 1;    //允许
    const CAN_PUBLISH_SPACES_OFF = 0;   //禁止

    //运营模式
    const MANUFACTURING_MODE_NONE = 0;                      //无对接
    const MANUFACTURING_MODE_BARRIER = 1;                   //道闸
    const MANUFACTURING_MODE_BARRIER_NAVIGATION = 2;        //道闸+室内导航
    const MANUFACTURING_MODE_BARRIER_CAMERA_NAVIGATION = 3; //道闸+车位摄像头+室内导航

    use SoftDeletes;

    protected $table = 'park_area';

    protected $fillable = [
        'name', 'code', 'attribute', 'status', 'car_model',
        'parking_places_count', 'temp_parking_places_count', 'floor',
        'fixed_parking_places_count', 'charging_pile_parking_places_count',
        'garage_height_limit', 'park_id', 'can_publish_spaces', 'manufacturing_mode',
        'defaulted_at'
    ];

    protected $dates = [
        'defaulted_at'
    ];

    protected $appends = [
        'has_default'
    ];

    public function setGarageHeightLimitAttribute($value) {
        $this->attributes['garage_height_limit'] = $value * 100;
    }

    public function getGarageHeightLimitAttribute($value) {
        return $value / 100;
    }

    /**
     * 所属停车场
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function park() {
        return $this->belongsTo(Park::class);
    }

    /**
     * 设备品牌模板
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function brands() {
        return $this->belongsToMany(Brand::class, 'park_area_brands');
    }

    /**
     * 车位
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function spaces() {
        return $this->hasMany(ParkSpace::class)->withCount('locks');
    }

    /**
     * 摄像头分组
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cameraGroups() {
        return $this->hasMany(ParkCameraGroup::class, 'park_area_id');
    }

    /**
     * 摄像头
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cameras() {
        return $this->hasMany(ParkCamera::class);
    }

    /**
     * 蓝牙
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bluetooths() {
        return $this->hasMany(ParkBluetooth::class);
    }

    /**
     * 地锁
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function locks() {
        return $this->hasMany(ParkSpaceLock::class);
    }

    /**
     * 查询条件过滤
     * @param Builder $query
     * @param Request $request
     * @return Builder
     */
    public function scopeSearch(Builder $query, Request $request) {
        if ($park_id = $request->input('park_id')) {
            $query->where('park_id', '=', $park_id);
        }
        return $query;
    }

    public function scopeDefault(Builder $query, $default = false)
    {
        return !$default ? $query->whereNull('defaulted_at') : $query->whereNotNull('defaulted_at');
    }

    public function getHasDefaultAttribute()
    {
        return $this->hasDefault();
    }

    public function hasDefault()
    {
        return !is_null($this->defaulted_at);
    }

    public function default($default = true)
    {
        $this->defaulted_at = $default ? now() : null;

        $this->save();

        if ($default) {
            static::query()->where('park_id', $this->park_id)
                ->where('id', '<>', $this->getKey())
                ->update([
                    'defaulted_at' => null
                ]);
        }
    }

}
