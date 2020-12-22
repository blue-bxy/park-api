<?php

return [
    'drivers' => [
        // 短信
        'sms' => [
            'key' => env('ALI_YUN_SMS_ID', env('ALI_YUN_ACCESS_ID', '')),
            'secret' => env('ALI_YUN_SMS_ID', env('ALI_YUN_ACCESS_SECRET', '')),
        ],
        // 内容安全审核
        'green' => [
            'key' => env('ALI_YUN_GREEN_ID', env('ALI_YUN_ACCESS_ID', '')),
            'secret' => env('ALI_YUN_GREEN_SECRET', env('ALI_YUN_ACCESS_SECRET', '')),
            'regionId' => env('ALI_YUN_GREEN_REGION_ID', 'cn-shanghai')
        ]
    ]
];
