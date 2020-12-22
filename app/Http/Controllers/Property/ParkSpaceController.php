<?php

namespace App\Http\Controllers\Property;

use App\Http\Controllers\Admin\BaseController;
use App\Http\Resources\Property\ParkRateResource;
use App\Http\Resources\Property\ParkSpaceResource;
use App\Models\Parks\ParkSpace;
use App\Services\ParkSpaceService;
use Illuminate\Http\Request;

class ParkSpaceController extends BaseController
{
    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $spaces = ParkSpace::query()
            ->with(['locks', 'carApt', 'carStop'])
            ->where('park_id', '=', $request->user()->park->id)
            ->search($request)
            ->paginate($request->input('per_page'));
        return ParkSpaceResource::collection($spaces);
    }

    /**
     * 车位数量统计
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function count(Request $request) {
        $spaces = ParkSpace::query()
            ->where('park_id', '=', $request->user()->park->id);
        if ($park_area_id = $request->input('park_area_id')) {
            $spaces->where('park_area_id', '=', $park_area_id);
        }
        $spaces = $spaces->get();
        $fixed = 0;
        $unpublished = 0;
        $used = 0;
        $disabled = 0;
        $reserving = 0;
        $reserved = 0;
        foreach ($spaces as $space) {
            if ($space->type == ParkSpace::TYPE_FIXED) {
                $fixed++;
            } else {
                switch ($space->status) {
                    case ParkSpace::STATUS_UNPUBLISHED: $unpublished++;
                    break;
                    case ParkSpace::STATUS_DISABLED: $disabled++;
                    break;
                    case ParkSpace::STATUS_RESERVING: $reserving++;
                    $used++;
                    break;
                    case ParkSpace::STATUS_RESERVED: $reserved++;
                    $used++;
                    break;
                    default: $used++;
                }
            }
        }
        $counts = compact('fixed', 'unpublished', 'used', 'disabled', 'reserving', 'reserved');
        $counts['all'] =  count($spaces);
        return $this->responseData($counts);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $space = ParkSpace::query()
            ->with(['carApt.userCar'])
            ->find($id);
        return $this->responseData(ParkSpaceResource::make($space));
    }

    /**
     * 费率详情
     * @param int $id
     * @return ParkRateResource|\Illuminate\Http\JsonResponse
     */
    public function rate($id) {
        $space = ParkSpace::query()->find($id);
        if (empty($space)) {
            return $this->responseNotFound();
        }
        $rate = $space->rates()->first();
        if (empty($rate)) {
            return $this->responseNotFound();
        }
        return $this->responseData(ParkRateResource::make($rate));
    }

    /**
     * Update the specified resource in storage.
     * @param ParkSpaceService $service
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\InvalidArgumentException
     */
    public function update(ParkSpaceService $service, Request $request, $id)
    {
        $space = ParkSpace::query()
            ->where('park_id', '=', $request->user()->park->id)
            ->find($id);
        if (empty($space)) {
            return $this->responseNotFound();
        }
        $space->fill($request->input());
        $service->update($space);
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
