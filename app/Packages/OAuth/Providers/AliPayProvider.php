<?php


namespace App\Packages\OAuth\Providers;


use App\Packages\Common\Signer;
use App\Packages\OAuth\AccessTokenInterface;
use App\Packages\OAuth\User;

class AliPayProvider extends AbstractProvider
{
    protected $baseUrl = "https://openapi.alipay.com/gateway.do";

    protected $signType = "RSA2";

    protected $version = "1.0";

    protected $charset = 'UTF-8';

    protected $privateKey;

    protected $scopes = ['auth_user'];

    protected $bit_content;

    protected static $genders = [
        'm' => 1,
        'f' => 2,
    ];

    protected function getDefaultData()
    {
        return [
            'app_id' => $this->clientId,
            'timestamp' => date('Y-m-d H:i:s'),
            'format' => 'json',
            'charset' => $this->charset,
            'sign_type' => $this->signType,
            'version' => $this->version
        ];
    }

    public function getAccessToken($code)
    {
        $data = $this->getTokenFields($code);

        $data['sign'] = $this->sign($data, $this->signType);

        $response = $this->getHttpClient()->get($this->baseUrl, [
            'query' => $data
        ]);

        $data = $response->getBody();

        if (!is_array($data)) {
            $data = json_decode($data, true);
        }

        $body = $data['alipay_system_oauth_token_response'] ?? [];

        return $this->parseAccessToken($body);
    }

    /**
     * @inheritDoc
     */
    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase('https://openauth.alipay.com/oauth2/publicAppAuthorize.htm', $state);
    }

    /**
     * @inheritDoc
     */
    protected function getTokenUrl()
    {
        return $this->baseUrl;
    }

    /**
     * @inheritDoc
     */
    protected function getCodeFields($state = null)
    {
        $fields = [
            'app_id' => $this->clientId,
            'scope' => $this->formatScopes($this->scopes, $this->scopeSeparator),
            'redirect_uri' => $this->redirectUrl,
        ];

        if ($this->usesState()) {
            $fields['state'] = $state;
        }

        return $fields;
    }

    protected function getTokenFields($code)
    {
        return $this->getDefaultData() + [
                'code' => $code,
                'method' => 'alipay.system.oauth.token',
                'grant_type' => 'authorization_code',
            ];
    }

    /**
     * @inheritDoc
     */
    protected function getUserByToken(AccessTokenInterface $token)
    {
        $headers = [
            'Content-Type' => 'application/x-www-form-urlencoded;charset='.$this->charset
        ];

        $data = array_merge($this->getDefaultData(), [
            'method' => 'alipay.user.info.share',
            'auth_token' => $token->getToken(),
            'bit_content' => is_array($this->bit_content) || is_object($this->bit_content)
                ? json_encode($this->bit_content) : $this->bit_content
        ]);

        $data['sign'] = $this->sign($data, $this->signType);

        $response = $this->getHttpClient()->get($this->baseUrl, [
            'headers' => $headers,
            'query' => $data
        ]);

        $result = json_decode($response->getBody(), true);

        return $result['alipay_user_info_share_response'] ?? [];

    }

    /**
     * @inheritDoc
     */
    protected function mapUserToObject(array $user)
    {
        return new User([
            'id' => $this->arrayItem($user, 'user_id'),
            'unionid' => $this->arrayItem($user, 'unionId'),
            'nickname' => $this->arrayItem($user, 'nick_name'),
            'name' => $this->arrayItem($user, 'nick_name'),
            'email' => $this->arrayItem($user, 'email'),
            'avatar' => $this->arrayItem($user, 'avatar'),
            'sex' => static::$genders[strtolower($this->arrayItem($user, 'gender'))] ?? 0, //F、M
        ]);
    }

    protected function sign($params, $signType)
    {
        $signer = new Signer($params);

        $signer->setIgnores(['sign']);

        $signType = strtoupper($signType);

        if ($signType == 'RSA') {
            $sign = $signer->signWithRSA($this->privateKey);
        } elseif ($signType == 'RSA2') {
            $sign = $signer->signWithRSA($this->privateKey, OPENSSL_ALGO_SHA256);
        } else {
            throw new \InvalidArgumentException('The signType is invalid');
        }

        return $sign;
    }

    public function setSignType($value)
    {
        $this->signType = $value;

        return $this;
    }

    public function setCharset($value)
    {
        $this->charset = $value;

        return $this;
    }

    public function setBitContent($value)
    {
        $this->bit_content = $value;

        return $this;
    }
}
