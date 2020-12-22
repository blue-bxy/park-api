<?php

namespace App\Http\Controllers\Admin;

use App\Exports\Admin\CouponRuleExport;
use App\Http\Requests\DiscountRuleRequest;
use App\Http\Resources\Admin\DiscountRuleResource;
use App\Models\Admin;
use App\Models\Coupons\CouponRule;
use Illuminate\Http\Request;

class DiscountRuleController extends BaseController
{
    use ExcelExport;

    protected function setExportParameters(Request $request) {
        $this->export = new CouponRuleExport($request);
        $this->fileName = '优免规则表';
    }

    /**
     * 优免规则列表数据
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $rules = CouponRule::query()
            ->search($request)
            ->with('user')
            ->latest()
            ->paginate($request->input('per_page'));
        return DiscountRuleResource::collection($rules);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param DiscountRuleRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(DiscountRuleRequest $request)
    {
        CouponRule::query()->create(array_merge($request->validated(), [
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
        $rule = CouponRule::query()->find($id);
        if (empty($rule)) {
            return $this->responseNotFound('请求的优免规则不存在！');
        }
        return $this->responseData(DiscountRuleResource::make($rule));
    }

    /**
     * Update the specified resource in storage.
     * @param DiscountRuleRequest $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(DiscountRuleRequest $request, $id)
    {
        $rule = CouponRule::query()->find($id);
        if (empty($rule)) {
            return $this->responseNotFound('请求的优免规则不存在！');
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
//            $query = CouponRule::query();
//
//            $query->where('id',$id)->delete();
//
//        } catch (QueryException $exception){
//
//            return $this->responseFailed('删除数据失败');
//
//        }
//            return $this->responseSuccess();
    }
}
