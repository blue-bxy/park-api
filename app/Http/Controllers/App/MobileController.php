<?php

namespace App\Http\Controllers\App;

use App\Http\Requests\MobileServiceRequest;
use App\Models\User;
use App\Services\ValidateService;
use Illuminate\Http\Request;

class MobileController extends BaseController
{
    /**
     * 绑定手机号
     *
     * @param MobileServiceRequest $request
     * @param ValidateService $service
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\InvalidArgumentException
     */
    public function bind(MobileServiceRequest $request, ValidateService $service)
    {
        $service->checkCode($request);

        /** @var User $user */
        $user = $request->user();

        $mobile = $request->input('mobile');

        $exists = User::query()->where('mobile', $mobile)
            ->where('id', '<>', $user->id)
            ->exists();

        if ($exists) {
            return $this->responseFailed('手机号已被使用，无法绑定', 40013);
        }

        $user->mobile = $mobile;
        $user->save();

        return $this->responseSuccess();
    }
}
