<?php

namespace App\Http\Controllers\Admin;

use App\Exports\Admin\FinancialManageDiscount;
use App\Http\Requests\DiscountRequest;
use App\Http\Resources\Admin\DiscountResource;
use App\Models\Admin;
use App\Models\Coupons\Coupon;
use App\Models\Coupons\CouponParkRule;
use App\Models\Coupons\CouponRule;
use App\Models\Coupons\CouponUserRule;
use App\Models\Users\UserCoupon;
use App\Services\CouponService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DiscountController extends BaseController
{
    use ExcelExport;

    protected function setExportParameters(Request $request) {
        $this->export = new FinancialManageDiscount($request);
        $this->fileName = '优惠券表';
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $coupons = Coupon::query()
            ->search($request)
            ->latest()
            ->paginate($request->input('per_page'));
        return DiscountResource::collection($coupons);
    }

    /**
     * 批量发放优惠券
     * @param DiscountRequest $request
     * @param CouponService $service
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(DiscountRequest $request, CouponService $service)
    {
        try {
            DB::transaction(function () use ($request, $service) {
                $service->store(array_merge($request->validated(), [
//                    'method' => $request->input('method'),
                    'publisher_type' => Admin::class,
                    'publisher_id' => $request->user()->id
                ]));
            });
        } catch (\Exception $exception) {
            logger($exception);
            return $this->responseFailed('优惠券添加失败', 40022);
        }

        // 成功
        return $this->responseSuccess();
    }

    /**
     * Display the specified resource.
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $coupon = Coupon::query()->find($id);
        if (empty($coupon)) {
            return $this->responseNotFound('请求的优惠券不存在！');
        }
        return $this->responseData(DiscountResource::make($coupon));
    }

    /**
     * 作废
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function invalid($id) {
        $coupon = Coupon::query()->find($id);
        if (empty($coupon)) {
            return $this->responseNotFound('请求的优惠券不存在！');
        }
        $coupon->is_valid = 0;
        $coupon->save();
        //todo
        UserCoupon::query()->where('coupon_id', '=', $coupon->id)
            ->where('status', '=', 'pending')
            ->update(['status' => 'invalid']);
        return $this->responseSuccess();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function rules(Request $request)
    {
        return [
            'coupon_rules'      => CouponRule::query()->search($request)->select('id', 'title')->get(),
            'coupon_user_rules' => CouponUserRule::query()->search($request)->select('id', 'title')->get(),
            'coupon_park_rules' => CouponParkRule::query()->search($request)->select('id', 'title')->get()
        ];
    }
}
