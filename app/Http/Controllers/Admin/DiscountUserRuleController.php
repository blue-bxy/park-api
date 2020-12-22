<?php

namespace App\Http\Controllers\Admin;

use App\Exports\Admin\CouponUserRuleExport;
use App\Http\Requests\DiscountUserRuleRequest;
use App\Http\Resources\Admin\DiscountUserRuleResource;
use App\Models\Admin;
use App\Models\Coupons\CouponUserRule;
use Illuminate\Http\Request;

class DiscountUserRuleController extends BaseController
{
    use ExcelExport;

    protected function setExportParameters(Request $request) {
        $this->export = new CouponUserRuleExport($request);
        $this->fileName = '优免用户规则';
    }

    /**
     * 优免用户列表数据
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $rules = CouponUserRule::query()->latest()->search($request)->paginate($request->input('per_page'));
        return DiscountUserRuleResource::collection($rules);
    }

    /**
     * Store a newly created resource in storage.
     * @param DiscountUserRuleRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(DiscountUserRuleRequest $request)
    {
        CouponUserRule::query()->create(array_merge($request->validated(), [
            'user_type' => Admin::class,
            'user_id' => $request->user()->id
        ]));
        return $this->responseSuccess();
    }

    /**
     * Display the specified resource.
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $coupon = CouponUserRule::query()->find($id);
        if (empty($coupon)) {
            return $this->responseNotFound();
        }
        return $this->responseData(DiscountUserRuleResource::make($coupon));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $rule = CouponUserRule::query()->find($id);
        if (empty($rule)) {
            return $this->responseNotFound();
        }
        $rule->is_active = $request->input('is_active');
        $rule->save();
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
//        try{
//            $query = CouponUserRule::query();
//            $query->where('id',$id)->delete();
//        }catch (QueryException $exception){
//            return $this->responseFailed('删除数据失败');
//        }
//        return $this->responseSuccess();

    }
}
