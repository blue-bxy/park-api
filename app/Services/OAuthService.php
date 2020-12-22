<?php


namespace App\Services;


use App\Exceptions\BadMethodException;
use App\Exceptions\InvalidArgumentException;
use App\Exceptions\SocialiteResponseUserFailException;
use App\Exceptions\UserMobileNotBindException;
use App\Models\User;
use App\Models\Users\UserAuthAccount;
use App\Packages\OAuth\Socialite;
use App\Rules\PhoneNumberValidate;
use Illuminate\Http\Request;

class OAuthService
{
    const LOGIN_TYPE_BY_PASSWORD = 'password';
    const LOGIN_TYPE_BY_CODE = 'code';

    /**
     * loginByMobile
     *
     * @param Request $request
     * @return array
     * @throws BadMethodException
     * @throws InvalidArgumentException
     */
    public function loginByMobile(Request $request)
    {
        // 手机号登陆方式1：密码登陆、2：验证码登陆，3：运营商快速登陆（未实现）
        $request->validate([
            'mobile' => ['required', new PhoneNumberValidate()],
            'type' => 'required',
            'password' => 'required_if:type,password',
            'sms_code' => 'required_if:type,code|size:4',
            'sms_key'  => 'required_if:type,code|string',
            'from' => 'sometimes|required|string',
            'openid' => 'sometimes|required|string',
            'unionid' => 'sometimes|required|string',
        ], [
            'sms_code.required_if' => '验证码不能为空',
            'sms_code.size' => '无效的验证码',
            'sms_key.required_if' => ':attribute不能为空',
        ]);

        list($type, $credentials) = $this->getCredential($request);
        // 验证码校验
        if ($type == self::LOGIN_TYPE_BY_CODE) {
            $this->validateCode($request, $credentials);
        }

        // 创建用户时 如果仅手机号注册，应设置默认头像
        $user = \DB::transaction(function () use ($request) {
            return tap(User::query()->firstOrNew([
                'mobile' => $request->input('mobile')
            ]), function (User $user) use ($request){
                // 默认头像
                $avatar = '';

                $nickname = "User_".str_random(12);

                if ($oath_account = $request->only('from', 'openid', 'unionid')) {
                    $from = $oath_account['from'];
                    $openid = $oath_account['openid'];
                    $unionid = $oath_account['unionid'] ?? null;

                    /** @var UserAuthAccount $account */
                    $account = UserAuthAccount::query()->where('from', $from)
                        ->where(function ($query) use ($openid, $unionid) {
                            $query->where('openid', $openid)
                                ->when($unionid, function ($query) use ($unionid) {
                                    $query->where('unionid', $unionid);
                                });
                        })->first();

                    if ($account) {
                        $account->user()->associate($user);

                        $account->save();

                        $nickname = $account->nickname;
                        $avatar = $account->avatar;
                    }
                }

                if (is_null($user->nickname)) {
                    $user->nickname = $nickname;
                }

                if (is_null($user->headimgurl)) {
                    $user->headimgurl = $avatar;
                }

                $user->save();
            });
        });


        if ($type == self::LOGIN_TYPE_BY_PASSWORD && $this->validatePassword($user, $credentials)) {
            throw new InvalidArgumentException('登陆失败', 40002);
        }

        return $this->login($user);
    }

    /**
     * 第三方授权登陆
     *
     * @param Request $request
     * @param string $service
     * @return array
     * @throws SocialiteResponseUserFailException
     * @throws UserMobileNotBindException
     */
    public function loginBySocialite(Request $request, string $service)
    {
        return $this->socialiteAuth($request, $service);
    }


    /**
     * getSocialiteAuthUser
     *
     * @param string $driver
     * @return \App\Packages\OAuth\User
     * @throws SocialiteResponseUserFailException
     */
    public function getSocialiteAuthUser(string $driver)
    {
        try {
            /** @var \App\Packages\OAuth\User $socialiteUser */
            $socialiteUser = Socialite::driver($driver)->stateless()->user();
            logger('socialite user by '. $driver, $socialiteUser->toArray());
        } catch (\Exception $exception) {
            logger($exception);
            throw new SocialiteResponseUserFailException();
        }

        return $socialiteUser;
    }

    /**
     * getSocialiteAttributes
     *
     * @param \App\Packages\OAuth\User $socialiteUser
     * @return array
     */
    public function getSocialiteAttributes($socialiteUser)
    {
        return [
            'unionid' => $socialiteUser->getUnionid(),
            'nickname' => $socialiteUser->getNickname() ?? "User_".str_random(12),
            'province' => $socialiteUser->getOriginal()['province'] ?? null,
            'city' => $socialiteUser->getOriginal()['city'] ?? null,
            'sex' => $socialiteUser->getSex(),
            'avatar' => $socialiteUser->getAvatar(),
            'access_token' => $socialiteUser->getAccessToken()->getToken(),
            'access_token_expired_at' => $socialiteUser->getAccessToken()->expired_at,
            'refresh_token' => $socialiteUser->getAccessToken()->refresh_token ?? null,
            'raw' => $socialiteUser->getOriginal()
        ];
    }

    /**
     * socialiteAuth
     *
     * @param Request $request
     * @param string $driver
     * @return array
     * @throws SocialiteResponseUserFailException
     * @throws UserMobileNotBindException
     */
    public function socialiteAuth(Request $request, string $driver)
    {
        $socialiteUser = $this->getSocialiteAuthUser($driver);

        $values = $this->getSocialiteAttributes($socialiteUser);

        $account = UserAuthAccount::query()
            ->with('user')
            ->updateOrCreate([
                'from' => $driver,
                'openid' => $socialiteUser->getId()
            ], $values);

        $user = $account->user;

        // 不存在用户绑定或用户未绑定手机号，让其走手机登陆
        if (!$user || ($user && !$user->mobile)) {
            $data = [
                'from' => $account->from,
                'openid' => $account->openid,
                'unionid' => $account->unionid,
            ];

            throw new UserMobileNotBindException($data, '已授权，请绑定手机号', 60002);
        }

        return $this->login($user);
    }

    /**
     * getCredential
     *
     * @param Request $request
     * @return array
     */
    protected function getCredential(Request $request)
    {
        $type = $request->input('type');

        $credentials = $type == self::LOGIN_TYPE_BY_PASSWORD ? [
            'password' => $request->input('password')
        ]: [
            'sms_code' => $request->input('sms_code'),
            'sms_key' => $request->input('sms_key'),
        ];

        return [$type, $credentials];
    }

    /**
     * 通过用户密码验证
     *
     * @param User $user
     * @param array $credentials
     * @return bool
     */
    protected function validatePassword(User $user, array $credentials)
    {
        return \Hash::check($credentials['password'], $user->password);
    }

    /**
     * 通过验证码验证
     *
     * @param Request $request
     * @param array $credentials
     * @return bool
     * @throws InvalidArgumentException
     */
    protected function validateCode(Request $request, array $credentials)
    {
        $verifyData = \Cache::get($credentials['sms_key']);

        if (empty($verifyData)) {
            throw new InvalidArgumentException('验证码已失效', 30022);
        }

        if (! hash_equals((string) $verifyData['code'], $credentials['sms_code'])) {
            throw new InvalidArgumentException('验证码错误，请重新输入', 30023);
        }

        if (! hash_equals((string) $request->input('mobile'), $verifyData['phone'])) {
            throw new InvalidArgumentException('手机号不一致，请输入正确的手机号', 30024);
        }

        \Cache::forget($credentials['sms_key']);

        return true;
    }

    /**
     * login
     *
     * @param User $user
     * @return array
     */
    public function login(User $user)
    {
        // 此处仅输出token，若需要返回用户基本信息，改造此处
        $token = $user->createToken('app')->plainTextToken;

        return [
            'nickname' => $user->nickname,
            'avatar' => $user->avatar(),
            'has_bind_mobile' => $user->hasBindMobile(),
            'token' => $token
        ];
    }
}
