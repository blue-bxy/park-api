<?php

namespace App\Http\Controllers\Property;

use App\Events\Login;
use App\Events\Logout;
use App\Http\Controllers\Admin\BaseController;
use App\Http\Requests\LoginRequest;
use App\Models\Property;
use Illuminate\Http\Request;
use Mews\Captcha\Captcha;

class LoginController extends BaseController
{
    /**
     * login
     *
     * @param LoginRequest $request
     * @return \Illuminate\Http\JsonResponse|void
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(LoginRequest $request)
    {
        $admin = Property::query()
            ->where('email', $request->input('email'))
            ->first();

        if ($admin && \Hash::check($request->input('password'), $admin->password)) {
            $token = $admin->createToken($admin->email)->plainTextToken;

            event(new Login($admin));

            $park = $admin->park;

            return $this->responseData([
                'token' => $token,
                'info' => [
                    'email' => $admin->email,
                    'nickname' => $admin->name,
                    'project_name' =>  empty($park) ? null : $park->project_name
                ]
            ]);
        }

        return $this->responseFailed('用户名或密码错误。', 40002);
    }

    public function username()
    {
        return 'email';
    }

    /**
     * The user has logged out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function logout(Request $request)
    {
        $user = $request->user();

        $user->tokens()->delete();

        event(new Logout($user));

        return $this->responseSuccess();
    }

    /**
     * 验证码
     *
     * @param Captcha $captcha
     * @param string $config
     * @return \Intervention\Image\ImageManager
     * @throws \Exception
     */
    public function captcha(Captcha $captcha, $config = 'default')
    {
        return $captcha->create($config, true);
    }

    /**
     * guard
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return auth()->guard('property');
    }
}
