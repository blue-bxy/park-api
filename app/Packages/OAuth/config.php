<?php

return [
    'default' => 'wechat',

    'gateways' => [
        'wechat' => [
            'driver' => 'WeChat',
            'clientId' => env('WECHAT_ACCOUNT_APPID', null),
            'clientSecret' => env('WECHAT_ACCOUNT_SECRET', null),
            'redirectUrl' => env('WECHAT_ACCOUNT_REDIRECT', null),
        ],
    ]
];
