<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends BaseController
{
    public function index(Request $request)
    {
        // 寻车 状态
        $find_status = false;

        if (!auth()->guest()) {
            /** @var User $user */
            $user = $request->user();

            // 登陆状态下
            $find_status = $user->orders()->whereHas('stop', function ($query) {
                // 用户已停好车且停车时长大于3分钟以上
                $query->hasFindCar(1);
            })->exists();
        }

        return $this->responseData(compact('find_status'));
    }

    public function info(Request $request)
    {
        $tel = settings('service_tel');

        return $this->responseData(compact('tel'));
    }
}
