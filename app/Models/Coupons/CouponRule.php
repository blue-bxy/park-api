<?php

namespace App\Models\Coupons;

use App\Models\Admin;
use App\Models\EloquentModel;
use App\Models\Traits\HasCoupon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;


class CouponRule extends EloquentModel
{
    use SoftDeletes, HasCoupon;

    public static $typeMaps = [
        1 => '小时券',
        2 => '现金券',
        3 => '折扣券',
        4 => '全免券',
    ];

    public static $sceneMaps = [
        1 => '通用',
        2 => '预约费',
        3 => '停车费'
    ];

    protected $fillable = [
        'title', 'amount', 'use_scene', 'desc', 'type', 'value', 'is_active', 'user_type', 'user_id'
    ];

    public function user()
    {
        return $this->morphTo();
    }

    public function getCouponRuleTypeColumn()
    {
        return 'type';
    }

    /**
     * 根据查询条件过滤查询范围
     * @param Builder $query
     * @param Request $request
     * @return  Builder
     */
    public function scopeSearch(Builder $query, Request $request)
    {
        if ($type = $request->input('type')) {
            $query->where('type', $type);
        }
        if ($title = $request->input('title')) {
            $query->where('title', 'like', "%$title%");
        }
        if (!is_null($amount = $request->input('amount'))) {
            $query->where('amount', '=', $amount);
        }
        if ($publisher = $request->input('publisher')) {
            $query->whereHasMorph('user', Admin::class, function (Builder $query) use ($publisher) {
                 $query->where('name', 'like', "%$publisher%");
            });
        }
        if ($createdAtFrom = $request->input('created_at_from')) {
            $query->whereDate('created_at', '>=', $createdAtFrom);
        }
        if ($createdAtTo = $request->input('created_at_to')) {
            $query->whereDate('created_at', '<=', $createdAtTo);
        }
        if (!is_null($is_active = $request->input('is_active'))) {
            $query->where('is_active', '=', $is_active);
        }
        return $query;
    }
}
