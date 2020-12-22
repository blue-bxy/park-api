<?php


namespace App\Http\Resources\Admin;


use App\Models\Coupons\CouponParkRule;
use App\Models\Coupons\CouponRule;
use App\Models\Coupons\CouponUserRule;
use Illuminate\Http\Resources\Json\JsonResource;
use function GuzzleHttp\Promise\queue;

class TypesResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'coupon_rules' => CouponRule::all()->toArray(),
            'coupon_user_rules' => CouponUserRule::all()->toArray(),
            'coupon_park_rules' => CouponParkRule::all()->toArray()
        ];
    }
}
