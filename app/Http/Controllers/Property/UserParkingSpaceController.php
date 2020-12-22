<?php

namespace App\Http\Controllers\Property;

use App\Http\Resources\Admin\UserParkingSpaceRespurce;
use App\Models\Property;
use App\Models\Users\UserParkingSpace;
use Illuminate\Http\Request;

class UserParkingSpaceController extends BaseController
{
    public function index(Request $request)
    {
        /** @var Property $user */
        $user = $request->user();

        $per_page = $request->input('per_page');

        $query = $user->userSpace()->getQuery();

        $query->with('user', 'park');

        if ($park_name = $request->input('park_name')) {
            $query->whereHas('park', function ($query) use ($park_name) {
                $query->where('park_name', 'like', "%$park_name%");
            });
        }

        if ($number = $request->input('number')) {
            $query->where('number', 'like', "%$number%");
        }

        $nickname = $request->input('nickname');
        $mobile = $request->input('mobile');

        $car_number = $request->input('car_number');

        if ($mobile || $nickname) {
            $query->whereHas('user', function ($query) use ($mobile, $nickname, $car_number) {
                $query->when($mobile, function ($query) use ($mobile) {
                    $query->where('mobile', 'like', "%$mobile%");
                });

                $query->when($nickname, function ($query) use ($nickname) {
                    $query->where('nickname', 'like', "%$nickname%");
                });

                $query->when($car_number, function ($query) use ($car_number) {
                    $query->whereHas('cars', function ($query) use ($car_number) {
                        $query->where('car_number', 'like', "%$car_number%");
                    });
                });
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
        $request->validate(['is_accept' => 'required|boolean']);

        /** @var Property $user */
        $user = $request->user();

        $space = $user->userSpace()->find($parking_id);

        if (!$space) {
            return $this->responseFailed('数据不存在', 40010);
        }

        $is_accept = $request->boolean('is_accept');

        if ($is_accept) {
            $space->status = UserParkingSpace::STATUS_FINISHED;
            $space->finished_at = now();
        } else {
            $space->status = UserParkingSpace::STATUS_FAILED;
            $space->failed_at = now();
        }

        $space->save();

        return $this->responseSuccess();
    }
}
