<?php

namespace App\Http\Controllers\Property;

use App\Http\Controllers\Admin\BaseController;
use App\Http\Resources\Property\ParkAreaResource;
use App\Models\Parks\ParkArea;
use Illuminate\Http\Request;

class ParkAreaController extends BaseController
{
    /**
     * 区域列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request) {
        $areas = ParkArea::query()
            ->where('park_id', '=', $request->user()->park->id)
            ->get();
        return $this->responseData(ParkAreaResource::collection($areas));
    }
}
