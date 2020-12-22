<?php

namespace App\Http\Controllers\App;

use App\Http\Requests\App\UserCarRequest;
use App\Http\Resources\App\UserCarResource;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Models\Users\UserCar;
use Illuminate\Http\Request;

class UserCarController extends BaseController
{
	/**
     * 我的车辆首页
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        /** @var User $user */
		$user = $request->user();

        $cars = $user->cars()
            ->latest('is_default')
            ->paginate();

        return $this->responseData(UserCarResource::collection($cars));
    }

	/**
     * 用户添加车辆
     *
     * @param UserCarRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function store(UserCarRequest $request)
    {
        /** @var User $user */
        $user = $request->user();

        $car_number = $request->input('car_number');

        $exists = UserCar::query()->where('car_number', $car_number)
            ->where('user_id', '<>', $user->getKey())
            ->exists();

        if ($exists) {
            return $this->responseFailed('该车牌已被绑定，请先解绑', 40022);
        }

        try {
            /** @var User $user */
            $user = $request->user();

            $car = $user->cars()->updateOrCreate([
                'car_number' => $request->input('car_number')
            ]);
        } catch (\Exception $e) {
            return $this->responseFailed('添加失败', 40022);
        }

        return $this->responseData(new UserCarResource($car));
    }

    /**
     * update
     *
     * @param UserCarRequest $request
     * @param UserCar $car
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(UserCarRequest $request, UserCar $car)
    {
        $this->authorize('own', $car);

        $car->car_number = $request->input('car_number');

        $car->save();

        return $this->responseData(new UserCarResource($car));
    }

    /**
     * 设置默认
     *
     * @param Request $request
     * @param UserCar $car
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function default(Request $request, UserCar $car)
    {
        $this->authorize('own', $car);

        $request->validate([
            'default' => 'required|boolean'
        ]);

        /** @var User $user */
        $user = $request->user();

        $car->is_default = $request->input('default');

        $car->save();

        $user->cars()
            ->where('id','<>', $car->id)
            ->update(['is_default' => false]);

        return $this->responseData(new UserCarResource($car));
    }

    /**
     * destroy
     *
     * @param UserCar $car
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Exception
     */
    public function destroy(UserCar $car)
    {
        $this->authorize('own', $car);

        $car->delete();

        return $this->responseSuccess();
    }
}
