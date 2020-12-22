<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\UsersResource;
use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends BaseController
{
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', $this->per_page);

        $query = User::query();

        if ($car_num = $request->input('car_num')) {
            $query->whereHas('cars', function ($query) use ($car_num) {
                $query->where('car_number', 'like', "%$car_num%");
            });
        }

        if ($mobile = $request->input('mobile')) {
            $query->where('mobile', 'like', "%$mobile%");
        }

        if ($nickname = $request->input('nickname')) {
            $query->where('nickname', 'like', "%$nickname%");
        }

        $query->with(['cars' => function ($query) {
            $query->latest('is_default')->latest('is_verify');
        }]);

        $query->latest();

        $users = $query->paginate($perPage);

        return UsersResource::collection($users);
    }

    public function unbind(Request $request, User $user)
    {
        $request->validate([
            'car_id' => 'required'
        ]);

        $car = $user->cars()
            ->where('id', $request->input('car_id'))
            ->first();

        if ($car) {
            $car->user_id = null;
            $car->is_default = false;
            $car->is_verify = false;
            $car->verified_at = null;
            $car->save();
        }

        return $this->responseSuccess();
    }


    /**
     * 删除
     *
     * @param Request $request
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, User $user)
    {
        $request->validate([
            'accept' => 'required|accepted'
        ], [
            'accept.accepted' => '请确认删除操作'
        ]);

        try {
            $user->delete();
        } catch (\Exception $exception) {
            return $this->responseFailed('删除失败，请检查', 40012);
        }

        return $this->responseSuccess();
    }
}
