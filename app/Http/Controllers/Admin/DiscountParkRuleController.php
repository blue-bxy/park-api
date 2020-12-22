<?php

namespace App\Http\Controllers\Admin;

use App\Exports\Admin\CouponParkRuleExport;
use App\Http\Requests\DiscountParkRuleRequest;
use App\Http\Resources\Admin\DiscountParkRuleResource;
use App\Models\Admin;
use App\Models\Coupons\CouponParkRule;
use Illuminate\Http\Request;

class DiscountParkRuleController extends BaseController
{
    use ExcelExport;

    protected function setExportParameters(Request $request) {
        $this->export = new CouponParkRuleExport($request);
        $this->fileName = '优免车场规则表';
    }

    /**
     * 优免车场列表数据
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $records = CouponParkRule::query()
            ->with('user')
            ->latest()
            ->search($request)
            ->paginate($request->input('per_page'));
        return DiscountParkRuleResource::collection($records);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param DiscountParkRuleRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(DiscountParkRuleRequest $request)
    {
        CouponParkRule::query()->create(array_merge($request->validated(), [
            'user_type' => Admin::class,
            'user_id' => $request->user()->id,
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
        $rule = CouponParkRule::query()->find($id);
        if (empty($rule)) {
            return $this->responseNotFound('请求的车场规则不存在！');
        }
        return $this->responseData(DiscountParkRuleResource::make($rule));
    }

    /**
     * Update the specified resource in storage.
     * @param DiscountParkRuleRequest $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(DiscountParkRuleRequest $request, $id)
    {
        $rule = CouponParkRule::query()->find($id);
        if (empty($rule)) {
            return $this->responseNotFound('请求的车场规则不存在！');
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
//            $query=CouponParkRule::query();
//
//            $query->where('id',$id)->delete();
//
//        }catch (\Illuminate\Database\QueryException $exception){
//            return $this->responseFailed('删除数据失败');
//        }
//        return $this->responseSuccess();
    }
}
