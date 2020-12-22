<?php

namespace App\Models\Parks;

use App\Models\Brand;
use App\Models\BrandModel;
use App\Models\EloquentModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

abstract class ParkDevice extends EloquentModel
{
    /**
     * 所属停车场
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function park() {
        return $this->belongsTo(Park::class);
    }

    /**
     * 所属区域
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function area() {
        return $this->belongsTo(ParkArea::class, 'park_area_id');
    }

    /**
     * 设备品牌
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function brand() {
        return $this->belongsTo(Brand::class);
    }

    /**
     * 设备型号
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function model() {
        return $this->belongsTo(BrandModel::class, 'brand_model_id');
    }

    /**
     * 管理的车位
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function spaces() {
        return $this->morphToMany(ParkSpace::class, 'device', 'park_space_has_devices');
    }

    /**
     * 查询范围过滤
     * @param Builder $query
     * @param Request $request
     * @return Builder
     */
    public function scopeSearch(Builder $query, Request $request) {
        if ($id = $request->input('id')) {
            return $query->where('id', '=', $request->input('id'));
        }
        if ($number = $request->input('number')) {
            $query->where('number', '=', $number);
        }
        if ($park_area_id = $request->input('park_area_id')) {
            $query->where('park_area_id', '=', $park_area_id);
        } elseif ($park_id = $request->input('park_id')) {
            $query->where('park_id', '=', $park_id);
        }
        if (!is_null($status = $request->input('status'))) {
            $query->where('status', '=', $status);
        }
        if (!is_null($network_status = $request->input('network_status'))) {
            $query->where('network_status', '=', $network_status);
        }
        if ($project_name = $request->input('project_name')) {
            $query->whereHas('park', function (Builder $query) use ($project_name) {
                $query->where('project_name', 'like', "%$project_name%");
            });
        }
        if ($park_area_name = $request->input('park_area_name')) {
            $query->whereHas('area', function (Builder $query) use ($park_area_name) {
                $query->where('name', 'like', "%$park_area_name%");
            });
        }
        return $query;
    }
}
