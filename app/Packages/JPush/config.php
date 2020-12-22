<?php

return [
    'jpush' => [
        'key' => env('JPUSH_APP_KEY', ''),
        'secret' => env('JPUSH_MASTER_SECRET', ''),
        'environment' => env('JPUSH_APNS_PRODUCTION', true),
        'log_file' => env('JPUSH_LOG_FILE', ''),
        'retry' => env('JPUSH_HTTP_RETRY', 5),
        'zone' => env('JPUSH_ZONE', null),
    ]
];
