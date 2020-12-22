<?php

namespace App\Http\Controllers\App;

use App\Services\ParkCallbackService;
use Illuminate\Http\Request;

class ParkCallbackController extends BaseController
{
    public function __invoke(Request $request, string $code, ParkCallbackService $service)
    {
        logger("停车场{$code}回调信息", $request->all());

        $service->handle($request, $code);
    }
}
