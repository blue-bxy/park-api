<?php

return [
    // The default gateway to use
    'default'  => 'wx_app',

    // Add in each gateway here
    'gateways' => [
        'wx_app' => [
            'driver'  => 'WechatPay_App',
            'options' => [
                'appId'     => env('WECHAT_ACCOUNT_APPID', ''),
                'mchId'     => env('WECHAT_PAY_MCH_ID', ''),
                'apiKey'    => env('WECHAT_PAY_API_KEY', ''),
                'notifyUrl' => env('WECHAT_PAY_NOTIFY_URI'),
                'certPath'  => storage_path('cert/apiclient_cert.pem'),
                'keyPath'   => storage_path('cert/apiclient_key.pem'),
            ],
        ],

        'wechat' => [
            'driver'  => 'WechatPay',
            'options' => [
                'appId'     => env('WECHAT_ACCOUNT_APPID', ''),
                'mchId'     => env('WECHAT_PAY_MCH_ID', ''),
                'apiKey'    => env('WECHAT_PAY_API_KEY', ''),
                'notifyUrl' => env('WECHAT_PAY_NOTIFY_URI'),
                'certPath'  => storage_path('cert/apiclient_cert.pem'),
                'keyPath'   => storage_path('cert/apiclient_key.pem'),
            ],
        ],

        'ali_app' => [
            'driver'  => 'Alipay_AopApp',
            'options' => [
                'environment'        => env('ALI_PAY_ENV', 'production'),
                'appId'           => env('ALI_PAY_ACCOUNT_APPID', ''),
                'signType'        => env('ALI_PAY_SIGN_TYPE', 'RSA2'),
                //开发者公钥
                'aliPayPublicKey' => storage_path('cert/alipay_public_key.pem'),
                //应用私钥
                'privateKey'      => storage_path('cert/alipay_rsa_private_key.pem'),
                'encryptKey'      => env('ALI_PAY_ENCRYPT_KEY', ''),
                'notifyUrl'       => env('ALI_PAY_NOTIFY_URI', ''),
            ],
        ],

        'ali_web' => [
            'driver'  => 'Alipay_AopPage',
            'options' => [
                'environment'        => env('ALI_PAY_ENV', 'production'),
                'appId'           => env('ALI_PAY_ACCOUNT_APPID', ''),
                'signType'        => env('ALI_PAY_SIGN_TYPE', 'RSA2'),
                //开发者公钥
                'aliPayPublicKey' => storage_path('cert/alipay_s_public_key.pem'),
                //应用私钥
                'privateKey'      => storage_path('cert/alipay_s_rsa_private_key.pem'),
                'encryptKey'      => env('ALI_PAY_ENCRYPT_KEY', ''),
                'notifyUrl'       => env('ALI_PAY_WEB_NOTIFY_URI', ''),
            ],
        ],

    ],
];
