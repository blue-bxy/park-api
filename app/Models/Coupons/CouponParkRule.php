<?php

namespace App\Models\Coupons;

use App\Models\Admin;
use App\Models\EloquentModel;
use App\Models\Regions\City;
use App\Models\Regions\Country;
use App\Models\Regions\Province;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class CouponParkRule extends EloquentModel
{
    use SoftDeletes;

    protected $fillable = [
        'title', 'turnover_rate', 'province_id', 'city_id', 'cooperate_days', 'park_property', 'is_active', 'desc',
        'user_type', 'user_id', 'district_id'
    ];

    protected $attributes = [
        'turnover_rate' => '',
        'cooperate_days' => 0
    ];

    public function province() {
        return $this->belongsTo(Province::class, 'province_id', 'province_id');
    }

    public function city() {
        return $this->belongsTo(City::class, 'city_id', 'city_id');
    }

    public function district() {
        return $this->belongsTo(Country::class, 'district_id', 'country_id');
    }

    public function user()
    {
        return $this->morphTo();
    }

    /**
     * 查询过滤
     * @param Builder $query
     * @param Request $request
     */
    public function scopeSearch(Builder $query, Request $request) {
        if ($title = $request->input('title')) {
            $query->where('title', 'like', "%$title%");
        }
        if ($parkProperty = $request->input('park_property')) {
            $query->where('park_property', '=', $parkProperty);
        }
        if ($createdAt = $request->input('created_at')) {
            $query->whereDate('created_at', '=', $createdAt);
        }
        if ($publisher = $request->input('publisher')) {
            $query->whereHasMorph('user', [Admin::class],  function (Builder $query) use ($publisher) {
                $query->where('name', 'like', "%$publisher%");
            });
        }
        if (!is_null($isActive = $request->input('is_active'))) {
            $query->where('is_active', '=', $isActive);
        }
    }
}
