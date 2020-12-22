<?php

namespace App\Models\Coupons;

use App\Exceptions\ApiResponseException;
use App\Exceptions\CouponNotFundException;
use App\Http\Requests\DiscountRequest;
use App\Models\EloquentModel;
use App\Models\Parks\Park;
use App\Models\Traits\HasCoupon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Users\UserCoupon;
use Illuminate\Http\Request;


class Coupon extends EloquentModel
{
    const DISTRIBUTION_METHODS = [
        1 => '平台推送',
        2 => 'app二维码',
        3 => '微信/支付宝二维码',
        4 => '分享链接',
        5 => '指定用户'
    ];

    use SoftDeletes, HasCoupon;

    public static $couponStatusMaps = [
        0 => '未使用',
        1 => '生效',
        2 => '已失效',
        3 => '已结束',
    ];

    protected $fillable = [
        'no', 'title', 'desc', 'icon', 'publisher_type', 'publisher_id',
        'coupon_rule_id', 'coupon_park_rule_id','coupon_user_rule_id','park_id',
        'coupon_rule_type', 'coupon_rule_value', 'use_scene', 'is_valid',
        'status', 'used_amount', 'quota', 'max_receive_num', 'take_count', 'used_count', 'need_integral_amount',
        'start_time', 'end_time', 'expired_at', 'valid_start_time', 'valid_end_time',
        'assign_user', 'qrcode_data', 'rules', 'distribution_method'
    ];

    protected $dates = [
        'start_time', 'end_time', 'expired_at', 'valid_start_time', 'valid_end_time'
    ];

    protected $appends = [
        'type_name', 'status_name'
    ];

    protected $casts = [
        'assign_user' => 'array',
        'rules' => 'array'
    ];

    /**
     * 关联用户的优惠券
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userCoupon()
    {
        return $this->hasMany(UserCoupon::class);
    }

    public function park()
    {
        return $this->belongsTo(Park::class)->withDefault();
    }

    public function publisher()
    {
        return $this->morphTo();
    }

    public function couponRule()
    {
        return $this->belongsTo(CouponRule::class)->withTrashed();
    }

    public function couponParkRule()
    {
        return $this->belongsTo(CouponParkRule::class)->withTrashed();
    }

    public function couponUserRule()
    {
        return $this->belongsTo(CouponUserRule::class)->withTrashed();
    }

    public function scopeCouponId(Builder $query, $coupon_id)
    {
        return $query->where(function ($query) use ($coupon_id) {
            $query->orWhere('id', $coupon_id)->orWhere('no', $coupon_id);
        });
    }

    public function scopeValid(Builder $query)
    {
        // 未过期且在时效内
        return $query->where('valid_start_time', '<', now())
            ->where('valid_end_time', '>', now());
            // ->where('expired_at', '>', now());
    }

    // public function amount()
    // {
    //     return $this->formatAmount($this->used_amount);
    // }
    //
    // public function withAmount()
    // {
    //     return $this->formatAmount($this->with_amount);
    // }

    public function getStatusNameAttribute()
    {
        return array_get(self::$couponStatusMaps, $this->status);
    }

    public function getCouponRuleTypeColumn()
    {
        return 'coupon_rule_type';
    }

    /**
     * 根据查询条件过滤查询范围
     * @param Builder $query
     * @param Request $request
     * @return  Builder
     */
    public function scopeSearch(Builder $query, Request $request)
    {
        if ($projectName = $request->input('project_name')) {
            $query->whereHas('park', function ($query) use ($projectName) {
                $query->where('project_name', 'like', "%$projectName%");
            });
        }

        if ($title = $request->input('title')) {
            $query->where('title' , 'like', "%$title%");
        }

        if ($couponRuleType = $request->input('coupon_rule_type')) {
            $query->where('coupon_rule_type', $couponRuleType);
        }

        if ($isValid = $request->input('is_valid')) {
            $query->where('is_valid', '=', $isValid);
        }

        if ($couponRuleId = $request->input('coupon_rule_id')) {
            $query->where('coupon_rule_id', '=', $couponRuleId);
        }

        if ($couponParkRuleId = $request->input('coupon_park_rule_id')) {
            $query->where('coupon_park_rule_id', '=', $couponParkRuleId);
        }

        if ($couponUserRuleId = $request->input('coupon_user_rule_id')) {
            $query->where('coupon_user_rule_id', $couponUserRuleId);
        }

        if ($no = $request->input('no')) {
            $query->where('no', $no);
        }

        if ($startDateFrom = $request->input('start_date_from')) {
            $query->whereDate('start_time', '>=', $startDateFrom);
        }

        if ($startDateTo = $request->input('start_date_to')) {
            $query->whereDate('start_time', '<=', $startDateTo);
        }

        if ($validStartDateFrom = $request->input('valid_start_date_from')) {
            $query->whereDate('valid_start_time', '>=', $validStartDateFrom);
        }

        if ($validStartDateTo = $request->input('valid_start_date_to')) {
            $query->whereDate('valid_start_time', '<=', $validStartDateTo);
        }

        if ($validEndDateFrom = $request->input('valid_end_date_from')) {
            $query->whereDate('valid_end_time', '>=', $validEndDateFrom);
        }

        if ($validEndDateTo = $request->input('valid_end_date_to')) {
            $query->whereDate('valid_end_time', '<=', $validEndDateTo);
        }

        return $query;
    }

    public function createQrcodeData()
    {
        $no = $this->no ?? get_order_no();

        try {
            $code = \QrCode::format('png')
                ->encoding('UTF-8')
                ->errorCorrection('H')
                ->generate($no);

            $this->qrcode_data = base64_encode($code);
        } catch (\Exception $e) {
            logger($e);
        }

        return $this;
    }

    public static function addCouponByRequest(DiscountRequest $request)
    {
        $coupon_rule_id = $request->input('coupon_rule_id');

        $rule = CouponRule::query()
            ->where('id', $coupon_rule_id)
            ->first();

        if (!$rule) {
            throw new ApiResponseException('优免规则不存在', 40022);
        }

        $start_time = $request->input('start_time');

        $coupon = new Coupon($request->all());

        $coupon->no = get_order_no();

        // 创建二维码数据
        $coupon->createQrcodeData();

        $coupon->now(new \DateTime($start_time));

        if ($coupon->valid_start_time <= now() && $coupon->valid_end_time >= now()) {
            $coupon->status = 1; // 生效
        }

        $coupon->use_scene = $rule->use_scene;
        $coupon->coupon_rule_type = $rule->type;
        $coupon->coupon_rule_value = $rule->type == 4 ? $rule->amount : $rule->value; // 全免券时值为优惠券金额

        $coupon->expired_at = $request->input('expired_time');
        $coupon->publisher()->associate($request->user());

        $coupon->save();
    }

    /**
     * 快速发放 设置时间
     *
     * @param \DateTimeInterface|null $start_time
     * @param \DateTimeInterface|null $end_time
     * @return $this
     */
    public function now(\DateTimeInterface $start_time = null, \DateTimeInterface $end_time = null)
    {
        $this->start_time = $start_time ?? now();

        $this->end_time = $end_time ?? $this->valid_end_time;

        return $this;
    }

    public static function getCouponBy($coupon_id)
    {
        return Coupon::query()
            ->where('status', 1) // 生效
            ->whereColumn('take_count', '!=', 'quota') // 有库存
            ->valid() // 在领取时间范围内
            ->lockForUpdate()
            ->couponId($coupon_id)
            ->firstOr(function () {
                throw new CouponNotFundException();
            });
    }

    public function getRemain()
    {
        return max($this->quota - $this->take_count, 0);
    }
}
