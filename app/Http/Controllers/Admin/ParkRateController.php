<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\InvalidArgumentException;
use App\Http\Requests\Admin\ParkRateRequest;
use App\Http\Resources\Admin\ParkRateResource;
use App\Models\Admin;
use App\Models\Parks\ParkRate;
use App\Services\ParkRateService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ParkRateController extends BaseController
{
    /**
     * 费率列表
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $rates = ParkRate::query()
            ->with(['park', 'area'])
            ->search($request)
            ->orderBy('updated_at', 'desc')
            ->paginate($request->input('per_page'));
        return ParkRateResource::collection($rates);
    }

    /**
     * 新建费率
     * @param ParkRateRequest $request
     * @param ParkRateService $service
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function store(ParkRateRequest $request, ParkRateService $service)
    {
        $data = array_merge($request->input(), [
            'no' => get_order_no(),
            'publisher_type' => Admin::class,
            'publisher_id' => $request->user()->id,
        ]);
        if (!in_array($data['type'], array(ParkRate::TYPE_PARK, ParkRate::TYPE_AREA))) {
            return $this->responseFailed('请选择合适费率类型！');
        }
        if ($data['type'] == ParkRate::TYPE_PARK) {
            $data['park_area_id'] = 0;
        }
        DB::transaction(function () use ($service, $data) {
            $service->store($data);
        });
        return $this->responseSuccess();
    }

    /**
     * 费率详情
     * @param $id
     * @return ParkRateResource|\Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $rate = ParkRate::query()->with(['park'])->find($id);
        if (empty($rate)) {
            return $this->responseNotFound('请求的费率不存在！');
        }
        return $this->responseData(ParkRateResource::make($rate));
    }

    /**
     * 更新费率
     * @param Request $request
     * @param ParkRateService $service
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function update(Request $request, ParkRateService $service, $id)
    {
        DB::transaction(function () use ($request, $service, $id) {
            $rate = ParkRate::query()->find($id);
            if (empty($rate)) {
                throw new InvalidArgumentException('请求的费率不存在！');
            }
            $rate->is_active = $request->input('is_active');
            $service->update($rate);
            return $rate;
        });
        return $this->responseSuccess();
    }

    /**
     * 删除费率
     * @param ParkRateService $service
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function destroy(ParkRateService $service, $id)
    {
        DB::transaction(function () use ($service, $id) {
            $rate = ParkRate::query()->with('spaces')->find($id);
            if (empty($rate)) {
                throw new InvalidArgumentException('请求的费率不存在！');
            }
            $service->delete($rate);
            return $rate;
        });
        return $this->responseSuccess();
    }
}
