<?php

namespace App\Models\Traits;

use App\Models\Coupons\CouponRule;

trait HasCoupon
{
    abstract public function getCouponRuleTypeColumn();

    public function getSceneNameAttribute()
    {
        return array_get(CouponRule::$sceneMaps, $this->use_scene, '未知的场景');
    }

    public function getTypeNameAttribute()
    {
        return array_get(CouponRule::$typeMaps, $this->{$this->getCouponRuleTypeColumn()}, '未知的类型');
    }
}
