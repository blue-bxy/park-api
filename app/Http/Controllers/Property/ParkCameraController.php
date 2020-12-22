<?php

namespace App\Http\Controllers\Property;

use App\Http\Resources\Property\ParkDeviceResource;
use App\Models\Parks\ParkCamera;
use Illuminate\Http\Request;

class ParkCameraController extends ParkDeviceController
{
    public function __construct(ParkCamera $camera) {
        parent::__construct($camera);
    }

    /**
     * 车位图片
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function pictures(Request $request) {
        $cameras = $this->device->with(['spaces' => function ($query) {
            $query->orderBy('number');
        }])
            ->where('park_id', '=', $request->user()->park->id)
            ->orderBy('rank')
            ->paginate($request->input('per_page'));
        return ParkDeviceResource::collection($cameras);
    }
}
