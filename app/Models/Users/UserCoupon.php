<?php

namespace App\Models\Users;

use App\Models\Coupons\Coupon;
use App\Models\EloquentModel;
use App\Models\Parks\Park;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

/**
 * Class UserCoupon
 * @package App\Models\Users
 *
 * @property-read boolean $has_used
 * @property-read int $coupon_type
 * @property-read boolean $use_case
 * @property-read boolean $has_expired
 */
class UserCoupon extends EloquentModel
{
    use SoftDeletes;

    const STATUS_PENDING = 'pending';
    const STATUS_USED = 'used';
    const STATUS_EXPIRED = 'expired';
    const STATUS_INVALID = 'invalid';

    public static $statusMaps = [
        self::STATUS_PENDING => '未使用',
        self::STATUS_USED => '已使用',
        self::STATUS_EXPIRED => '已失效',
        self::STATUS_INVALID => '已作废'
    ];

    protected $fillable = [
        'user_id', 'coupon_id', 'park_id', 'amount', 'use_min_amount', 'title', 'use_time', 'expiration_time',
        'start_time', 'end_time', 'status',
    ];

    protected $dates = [
        'start_time', 'end_time', 'expiration_time'
    ];

    protected static function boot()
    {
        parent::boot();

        // 用户领取后记录
        static::created(function (UserCoupon $coupon) {
            $coupon->coupon->increment('take_count', 1);
        });
        // 使用后记录
        static::saved(function (UserCoupon $coupon) {
            if ($coupon->isDirty('use_time') && $coupon->use_time) {
                $coupon->coupon->increment('used_count', 1);
            }
        });
    }

    public function getStatusRenameAttribute()
    {
        return self::$statusMaps[$this->status];
    }

    /**
     * getHasUseAttribute
     *
     * @example $has_used
     *
     * @return bool
     */
    public function getHasUsedAttribute()
    {
        return !is_null($this->use_time);
    }

    /**
     * getHasExpiredAttribute
     *
     * @return bool
     */
    public function getHasExpiredAttribute()
    {
        return $this->expiration_time < now();
    }

    public function park()
    {
        return $this->belongsTo(Park::class);
    }

    /**
     * scopeValid
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeValid(Builder $query)
    {
        return $query->where(function ($query) {
            return $query->orWhereNull('use_time')
                ->orWhere('expiration_time', '>', now())
                ->orWhere('status', '=', self::STATUS_PENDING);
        });
    }

    public function scopeExpired(Builder $query)
    {
        return $query->where(function ($query) {
            return $query->orWhereNotNull('use_time')
                ->orWhere('expiration_time', '<', now())
                ->orWhere('status', '!=', self::STATUS_PENDING);
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function order() {
        return $this->belongsTo(UserOrder::class, 'order_id', 'id');
    }

    public function scopeCouponId(Builder $query, $coupon_id)
    {
        return $query->whereHas('coupon', function ($query) use ($coupon_id) {
            $query->couponId($coupon_id);
        });
    }

    /**
     * 使用场景 true 特定停车场， false 全部
     *
     * @return bool
     */
    public function getUseCaseAttribute()
    {
        return $this->park_id > 0;
    }

    public function getDescriptionAttribute()
    {
        return $this->use_case ? $this->getParkDesc() : '平台合作停车场均可使用';
    }

    protected function getParkDesc()
    {
        return sprintf('仅%s停车场使用', $this->park->park_name);
    }

    public function getLimitAttribute()
    {
        $min_amount = $this->use_min_case/100;
        $amount = $this->amount/100;

        return $this->use_min_amount > 0 ? "满{$min_amount}减$amount" : '';
    }

    /**
     * 查询过滤
     * @param Builder $query
     * @param Request $request
     */
    public function scopeSearch(Builder $query, Request $request) {
        if ($title = $request->input('title')) {
            $query->where('title', '=', $title);
        }
        if ($status = $request->input('status')) {
            $query->where('status', '=', $status);
        }
        if ($distributionMethod = $request->input('distribution_method')) {
            $query->where('distribution_method', '=', $distributionMethod);
        }
        if ($no = $request->input('no')) {
            $query->where('no', '=', $no);
        }
        if ($createdAt = $request->input('created_at')) {
            $query->whereDate('created_at', '>=', $createdAt);
        }
        if ($userMobile = $request->input('user_mobile')) {
            $query->whereHas('user', function (Builder $query) use ($userMobile) {
                $query->where('mobile', '=', $userMobile);
            });
        }
    }
}
