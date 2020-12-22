<?php

namespace App\Http\Controllers\App;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Users\UserCoupon;
use App\Models\Coupons\Coupon;
use App\Http\Resources\App\UserCouponResource;

class UserCouponController extends BaseController
{
    /**
     * index
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
		$request->validate([
			'is_expired' => 'boolean'
		]);
		/** @var User $user */
		$user = $request->user();
        //分页数量
        $perPage = $request->input('per_page');

		$query = $user->coupon()->getQuery();

		$query->with('park');

		$is_expired = $request->boolean('is_expired', false);

		$is_expired ? $query->expired() : $query->valid();

		$query->oldest('expiration_time');

		$res = $query->latest()->paginate($perPage);

		return $this->responseData(UserCouponResource::collection($res));
    }

    /**
     * 领取优惠券
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function store(Request $request)
    {
        /** @var User $user */
        $user = $request->user();

        $coupon_id = $request->input('id');

        $coupon_info = Coupon::getCouponBy($coupon_id);

        if($coupon_info->take_count >= $coupon_info->quota){
            //优惠发完了
            return $this->responseFailed('优惠券发完了。',40010);
        }

        $user_coupon_num = $user->coupon()
            ->couponId($coupon_id)
            ->count();

        if ($coupon_info->max_receive_num <= $user_coupon_num) {
            //超出领取上限
            return $this->responseFailed('超出领取数量上限。',40010);
        }

        DB::transaction(function () use ($user, $coupon_info) {
            $coupon = new UserCoupon([
                'expiration_time' => $coupon_info->expired_at,
                'title' => $coupon_info->title,
                'park_id' => $coupon_info->park_id,
                'amount' => $coupon_info->used_amount,
                'start_time' => $coupon_info->valid_start_time,
                'end_time' => $coupon_info->valid_end_time,
                'use_min_amount' => 0 // 预留
            ]);

            $coupon->coupon()->associate($coupon_info);
            $coupon->user()->associate($user);

            $coupon->save();
        });

        return $this->responseSuccess();
    }

    public function scan(Request $request)
    {
        $request->validate([
            'no' => 'required|string'
        ], [
            'no.required' => '优惠券编号必须',
        ]);

        $no = $request->input('no');

        $coupon = Coupon::getCouponBy($no);

        return $this->responseData([
            'title' => $coupon->title,
            'quota' => $coupon->quota, // 配额
            'remain' => $coupon->getRemain(), // 剩余数量
            'limit' => $coupon->max_receive_num, // 领取上限
            'use_scene' => $coupon->use_scene, //使用场景
            'start_time' => $coupon->valid_start_time ? $coupon->valid_start_time->toDateTimeString() : null, // 使用时间范围
            'end_time' => $coupon->valid_end_time ? $coupon->valid_end_time->toDateTimeString() : null,
        ]);

    }
}
