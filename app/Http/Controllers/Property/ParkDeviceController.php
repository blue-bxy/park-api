<?php

namespace App\Http\Controllers\Property;

use App\Http\Resources\Property\ParkDeviceResource;
use App\Models\Parks\ParkDevice;
use Illuminate\Http\Request;

abstract class ParkDeviceController extends BaseController
{
    protected $device;

    public function __construct(ParkDevice $bluetooth) {
        $this->device = $bluetooth;
    }

    /**
     * 设备列表
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request) {
        return ParkDeviceResource::collection($this->device
            ->with(['area', 'brand', 'model'])
            ->where('park_id', '=', $request->user()->park->id)
            ->search($request)
            ->paginate($request->input('per_page'))
        );
    }
}
