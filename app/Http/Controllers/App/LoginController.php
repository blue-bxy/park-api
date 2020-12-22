<?php

namespace App\Http\Controllers\App;

use App\Exceptions\BadMethodException;
use App\Exceptions\SocialiteResponseUserFailException;
use App\Exceptions\UserMobileNotBindException;
use App\Models\User;
use App\Packages\OAuth\Socialite;
use App\Rules\PhoneNumberValidate;
use App\Services\OAuthService;
use App\Services\SmsService;
use Illuminate\Http\Request;
use App\Http\Requests\App\User\ResetPasswordRequest;
use Illuminate\Support\Str;

class LoginController extends BaseController
{
    /**
     * login
     *
     * @param Request $request
     * @param $service
     * @param OAuthService $authService
     * @return \Illuminate\Http\JsonResponse
     * @throws BadMethodException
     */
    public function login(Request $request, $service, OAuthService $authService)
    {
        $method = "loginBy".ucfirst(Str::camel($service));

        if (!method_exists($authService, $method)) {
            throw new BadMethodException("method [$method] 不存在");
        }

        return $this->responseData($authService->$method($request));
    }

    /**
     * socialite
     *
     * @param Request $request
     * @param $service
     * @return \Illuminate\Http\JsonResponse
     */
    public function socialite(Request $request, $service)
    {
        try {
            $socialite = Socialite::driver($service);

            if ($service == 'wechat') {
                $socialite->scopes(['snsapi_userinfo']);
            }

            return $this->responseData($socialite->response());
        } catch (\Exception $exception) {
            logger('获取oauth授权失败 service: '. $service, $exception->getTrace());
            return $this->responseFailed('获取授权基本信息失败', '50001');
        }
    }

    /**
     * 第三方授权回调
     *
     * @param Request $request
     * @param $service
     * @param OAuthService $authService
     * @return \Illuminate\Http\JsonResponse
     * @throws SocialiteResponseUserFailException
     * @throws UserMobileNotBindException
     */
    public function handleCallback(Request $request, $service, OAuthService $authService)
    {
        $response = $authService->loginBySocialite($request, $service);

        return $this->responseData($response);
    }

    /**
     * 手机验证码登陆-发送验证码
     *
     * @param Request $request
     * @param SmsService $service
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendCode(Request $request, SmsService $service)
    {
        $request->validate([
           'mobile' => ['required', new PhoneNumberValidate()]
        ]);

        $mobile = $request->input('mobile');

        // 验证码使用场景login:用于登陆验证码，其他根据具体业务决定
        $type = $request->input('type', 'login');

        // 验证码有效期
        $expiredAt = now()->addMinutes(10);

        if (app()->isLocal()) {
            $code = 1234;
        } else {
            try {
                $code = get_sms_code();
                // 调用第三方接口发送短信
                $service->setType($type)->setCode($code)->send($mobile);

            } catch (\Exception $exception) {
                return $this->responseFailed($exception->getMessage(), 60001);
            }
        }

        $key = 'verificationCode_'.str_random(15);

        \Cache::put($key, [
            'phone' => $mobile,
            'code' => $code,
            'type' => $type
        ], $expiredAt);

        return $this->responseData([
            'sms_key' => $key,
            'expired_at' => $expiredAt->toDateTimeString()
        ]);
    }

	/**
     * 重置密码
     *
     * @param ResetPasswordRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function resetPassword(ResetPasswordRequest $request)
    {
        try {
            /** @var User $user */
            $user = $request->user();

            $user->password = \Hash::make($request->input('password'));

            $user->save();
            // 更换密码后删除当前token并要求重新登录
            $user->tokens()->delete();

            return $this->responseSuccess('密码修改成功');
        } catch (\Exception $exception) {
            return $this->responseFailed($exception->getMessage(), 0);
        }
    }

	/**
     * 退出登录
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        /** @var User $user */
		$user = $request->user();

		$user->tokens()->delete();

        return $this->responseSuccess();
    }
}
