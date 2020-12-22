<?php

return [
    'default' => 'wechat',

    'gateways' => [
        'wechat'  => [
            'driver'       => 'WeChat',
            'clientId'     => env('WECHAT_ACCOUNT_APPID', null),
            'clientSecret' => env('WECHAT_ACCOUNT_SECRET', null),
            'redirectUrl'  => env('WECHAT_ACCOUNT_REDIRECT', null),
        ],
        //支付宝网页调APP支付
        'ali_pay' => [
            'driver'          => 'AliPay',
            'clientId'        => env('ALI_PAY_ACCOUNT_APPID', null),
            'signType'        => env('ALI_PAY_ACCOUNT_SIGN_TYPE', 'RSA2'),
            //开发者公钥
            'aliPayPublicKey' => storage_path('cert/alipay_public_key.pem'),
            //应用私钥
            'privateKey'      => storage_path('cert/alipay_rsa_private_key.pem'),
            'clientSecret'    => env('ALI_PAY_ACCOUNT_ENCRYPT_KEY', '')
        ],

        // 'qq' => [
        //     'driver'       => 'QQ',
        //     'clientId'     => env('QQ_ACCOUNT_APPID', null),
        //     'clientSecret' => env('QQ_ACCOUNT_ENCRYPT_KEY', null),
        //     'redirectUrl'  => env('QQ_ACCOUNT_REDIRECT', null),
        // ]
    ]
];
