<?php

return [
    // 车位锁
    'u_ber' => [
        // 默认 安徽优步车位锁
        'default' => [
            'client_id' => env('UBER_CLIENT_ID', ''),
            'client_key' => env('UBER_CLIENT_KEY', ''),
        ],
    ],

    // 丁丁停车
    'ding_ding' => [
        // 默认 安徽优步车位锁
        'default' => [
            'client_id' => env('DINGDING_CLIENT_ID', ''),
            'client_key' => env('DINGDING_CLIENT_KEY', ''),
        ],
    ],

    // 蜂寻
    'bee_find' => [
        'default' => [
            'client_id' => env('BEE_FIND_CLIENT_ID', ''),
            'client_key' => env('BEE_FIND_CLIENT_KEY', ''),
        ],
    ]
];
