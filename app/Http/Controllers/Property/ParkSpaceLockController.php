<?php

namespace App\Http\Controllers\Property;

use App\Http\Resources\Property\ParkDeviceResource;
use App\Models\Parks\ParkSpaceLock;
use Illuminate\Http\Request;

class ParkSpaceLockController extends ParkDeviceController
{
    public function __construct(ParkSpaceLock $lock) {
        parent::__construct($lock);
    }

    public function index(Request $request) {
        return ParkDeviceResource::collection($this->device
            ->with(['area', 'brand', 'model', 'space'])
            ->where('park_id', '=', $request->user()->park->id)
            ->search($request)
            ->paginate($request->input('per_page'))
        );
    }
}
