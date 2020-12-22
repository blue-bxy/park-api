<?php

namespace App\Http\Controllers\Admin;

use App\Exports\Admin\UserCouponExport;
use App\Http\Resources\Admin\UserCouponResource;
use App\Models\Users\UserCoupon;
use Illuminate\Http\Request;

class UserCouponController extends BaseController
{
    use ExcelExport;
    protected function setExportParameters(Request $request) {
        $this->export = new UserCouponExport($request);
        $this->fileName = '用户优惠券表';
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $coupons = UserCoupon::query()->with(['user', 'order'])
            ->search($request)->latest()->paginate($request->input('per_page'));
        return UserCouponResource::collection($coupons);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $coupon = UserCoupon::query()->find($id);
        if (empty($coupon)) {
            return $this->responseNotFound();
        }
        return $this->responseData(UserCouponResource::make($coupon));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * 作废
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function invalid(int $id) {
        $coupon = UserCoupon::query()->find($id);
        if (empty($coupon)) {
            return $this->responseNotFound();
        }
        if ($coupon->status !== 'pending') {
            return $this->responseFailed();
        }
        $coupon->status = 'invalid';
        $coupon->save();
        return $this->responseSuccess();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
