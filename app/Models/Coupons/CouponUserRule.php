<?php

namespace App\Models\Coupons;

use App\Models\EloquentModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class CouponUserRule extends EloquentModel
{
    use SoftDeletes;

    protected $fillable = [
        'title', 'is_activity_active', 'active_days', 'activity_setting_days', 'is_regression_active',
        'regression_days', 'is_new_user', 'is_active', 'desc', 'user_type', 'user_id',
    ];

    public function user()
    {
        return $this->morphTo();
    }

    /**
     * 查询条件过滤
     * @param Builder $query
     * @param Request $request
     */
    public function scopeSearch(Builder $query, Request $request) {
        if ($title = $request->input('title')) {
            $query->where('title', 'like', "%$title%");
        }
        if (!is_null($isActivityActive = $request->input('is_activity_active'))) {
            $query->where('is_activity_active', '=', $isActivityActive);
        }
        if (!is_null($isRegressionActive = $request->input('is_regression_active'))) {
            $query->where('is_regression_active', '=', $isRegressionActive);
        }
        if (!is_null($isNewUser = $request->input('is_new_user'))) {
            $query->where('is_new_user', '=', $isNewUser);
        }
        if ($createdAtFrom = $request->input('created_at_from')) {
            $query->whereDate('created_at', '>=', $createdAtFrom);
        }
        if ($createdAtTo = $request->input('created_at_to')) {
            $query->whereDate('created_at', '<=', $createdAtTo);
        }
        if (!is_null($isActive = $request->input('is_active'))) {
            $query->where('is_active', '=', $isActive);
        }
    }
}
