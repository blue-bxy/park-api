<?php

return [
    'key' => env('ALI_YUN_GREEN_ID', env('ALI_YUN_ACCESS_ID', '')),
    'secret' => env('ALI_YUN_GREEN_SECRET', env('ALI_YUN_ACCESS_SECRET', '')),
    'regionId' => env('ALI_YUN_GREEN_REGION_ID', 'cn-shanghai')
];
