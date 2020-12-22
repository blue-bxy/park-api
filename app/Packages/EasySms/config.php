<?php

return [
    // HTTP 请求的超时时间（秒）
    'timeout'  => 5.0,

    // 默认发送配置
    'default'  => [
        // 网关调用策略，默认：顺序调用
        'strategy' => \Overtrue\EasySms\Strategies\OrderStrategy::class,

        // 默认可用的发送网关
        'gateways' => [
            'aliyun'
        ],
    ],
    // 可用的网关配置
    'gateways' => [
        'errorlog' => [
            'file' => storage_path('logs/easysms.log'),
        ],

        'yunpian' => [
            'api_key' => '',
        ],

        'aliyun' => [
            'access_key_id'     => env('ALI_YUN_SMS_ID', ''),
            'access_key_secret' => env('ALI_YUN_SMS_SECRET', ''),
            'sign_name'         => env('SIGN_NAME', '弼马桩'),
        ],

        'qcloud' => [
            'sdk_app_id' => env('QCLOUD_SMS_APP_ID', ''), // SDK APP ID
            'app_key'    => env('QCLOUD_SMS_APP_KEY', ''), // APP KEY
        ],
    ],
];
