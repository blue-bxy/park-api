<?php

namespace App\Http\Controllers\Property;

use App\Exceptions\InvalidArgumentException;
use App\Http\Controllers\Admin\BaseController;
use App\Http\Requests\Property\ParkRateRequest;
use App\Http\Resources\Property\ParkRateResource;
use App\Models\Parks\ParkRate;
use App\Models\Property;
use App\Services\ParkRateService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ParkRateController extends BaseController
{
    /**
     * 费率列表
     * Display a listing of the resource.
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $rates = ParkRate::query()
            ->where('park_id', '=', $request->user()->park->id)
//            ->where('publisher_type', '=', Property::class)
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
            'publisher_type' => Property::class,
            'publisher_id' => $request->user()->id,
            'park_id' => $request->user()->park_id,
            'park_area_id' => 0
        ]);
        if ($data['type'] == ParkRate::TYPE_SPACE) {
            $data['name'] = get_order_no();
            $data['parking_spaces_count'] = 1;
        }
        DB::transaction(function () use ($service, $data) {
            $service->store($data);
        });
        return $this->responseSuccess();
    }

    /**
     * 费率详情
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $rate = ParkRate::query()->find($id);
        if (empty($rate)) {
            return $this->responseNotFound('请求的费率不存在！');
        }
        return $this->responseData(ParkRateResource::make($rate));
    }

    /**
     * 更新费率（只能设置状态）
     * @param Request $request
     * @param ParkRateService $service
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function update(Request $request, ParkRateService $service, $id)
    {
        DB::transaction(function () use ($request, $service, $id) {
            $rate = ParkRate::query()
                ->where('park_id', '=', $request->user()->park_id)
                ->find($id);
            if (empty($rate)) {
                throw new InvalidArgumentException('请求的费率不存在！');
            }
            if ($rate->publisher_type != Property::class) {
                throw new InvalidArgumentException('您无权修改该费率！');
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
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function destroy(ParkRateService $service, Request $request, $id)
    {
        DB::transaction(function () use ($service, $request, $id) {
            $rate = ParkRate::query()->with('spaces')
                ->where('park_id', '=', $request->user()->park->id)
                ->find($id);
            if (empty($rate)) {
                throw new InvalidArgumentException('请求的费率不存在！');
            }
            $service->delete($rate);
            return $rate;
        });
        return $this->responseSuccess();
    }
}
