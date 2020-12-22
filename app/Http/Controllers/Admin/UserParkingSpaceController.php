<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\UserParkingSpaceRespurce;
use App\Models\Users\UserParkingSpace;
use Illuminate\Http\Request;

class UserParkingSpaceController extends BaseController
{
    public function index(Request $request)
    {
        $per_page = $request->input('per_page');

        $query = UserParkingSpace::query();

        $query->with('user', 'park.property');

        if ($park_name = $request->input('park_name')) {
            $query->whereHas('park', function ($query) use ($park_name) {
                $query->where('park_name', 'like', "%$park_name%");
            });
        }

        if ($number = $request->input('number')) {
            $query->where('number', 'like', "%$number%");
        }

        if ($mobile = $request->input('mobile')) {
            $query->whereHas('user', function ($query) use ($mobile) {
                $query->where('mobile', 'like', "%$mobile%");
            });
        }

        $start_time = $request->input('start_time');
        $end_time = $request->input('end_time');

        if ($start_time && $end_time) {
            $query->whereBetween('created_at', [$start_time, $end_time]);
        }

        $items = $query->orderBy('id','desc')->paginate($per_page);

        return UserParkingSpaceRespurce::collection($items);
    }

    public function update(Request $request, $parking_id)
    {
        $request->validate(['has_allowed' => 'required|boolean']);

        $space = UserParkingSpace::query()->find($parking_id);

        if (!$space) {
            return $this->responseFailed('数据不存在', 40010);
        }

        if (!$space->hasVerified()) {
            return $this->responseFailed('物业端未通过，不允许开通', 40011);
        }

        $allowed = $request->boolean('has_allowed', false);

        $space->allowed_at = $allowed ? now() : null;

        $space->save();

        return $this->responseSuccess();
    }
}
