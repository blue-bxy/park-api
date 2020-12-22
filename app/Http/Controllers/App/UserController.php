<?php

namespace App\Http\Controllers\App;

use App\Exceptions\SocialiteResponseUserFailException;
use App\Models\Users\UserAuthAccount;
use App\Packages\OAuth\Socialite;
use App\Services\OAuthService;
use App\Services\UserService;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\App\UserResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\App\BalanceResource;
use Illuminate\Validation\Rule;

/**
 * 个人中心的控制器
 */
class UserController extends BaseController
{
    /**
     * 个人中心首页
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        /** @var User $user */
        $user = $request->user();

        // 懒加载 有效优惠券总数
        $user->loadCount('validCoupon');

		// 默认车牌号
		$default_car = $user->cars()
            ->where('is_default', true)
            ->first();

        return $this->responseData([
            'nickname' => $user->nickname,
            'mobile' => $user->mobile,
            'avatar' => $user->avatar,
            'integral' => $user->integral,
            'balance' => $user->balance,
            'coupon_count' => $user->valid_coupon_count,
			'car_number' => $default_car ? $default_car->car_number : null,
            'has_verify' => $user->is_verify
        ]);
    }

    /**
     * 我的-个人资料
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function profile(Request $request)
    {
        $user = $request->user();

        return $this->responseData(new UserResource($user));
    }

    /**
     * 更新个人资料
     *
     * @param Request $request
     * @param UserService $service
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\InvalidArgumentException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, UserService $service)
    {
        $this->validate($request, [
            'column' => ['required', Rule::in(['avatar', 'nickname', 'sex', 'email'])],
            'value' => 'required'
        ]);

        /** @var User $user */
        $user = $request->user();

        $service->updateProfile($user, $request);

        return $this->responseSuccess('修改成功');
    }

    public function account(Request $request)
    {
        /** @var User $user */
        $user = $request->user();

        $platforms = array_keys(config('oauth.gateways'));

        $accounts = $user->authAccount()->get();

        $data = collect($platforms)->mapWithKeys(function ($item) use ($accounts) {
            $bool = (bool) $accounts->filter(function ($account) use ($item) {
                return $account->from == $item;
            })->first();

            return [$item => $bool];
        });

        return $this->responseData($data);
    }

    /**
     * 绑定第三方账号
     *
     * @param Request $request
     * @param string $service
     * @param OAuthService $authService
     * @return \Illuminate\Http\JsonResponse
     * @throws SocialiteResponseUserFailException
     */
    public function bind(Request $request, string $service, OAuthService $authService)
    {
        $request->validate([
            'code' => 'required|string',
            'state' => 'required|string'
        ]);
        
        /** @var User $user */
        $user = $request->user();

        $socialiteUser = $authService->getSocialiteAuthUser($service);

        $user->accounts()->updateOrCreate([
            'from' => $service,
            'openid' => $socialiteUser->getId(),
        ], $authService->getSocialiteAttributes($socialiteUser));

        return $this->responseSuccess();
    }
}
