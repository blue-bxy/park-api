<?php

namespace App\Http\Controllers\App;

use App\Services\DeviceCallbackService;
use Illuminate\Http\Request;

class DeviceCallbackController extends BaseController
{
    public function __invoke(Request $request, string $device)
    {
        //
        logger("设备 ".$device." 回调通知", $request->all());

        try {
            $service = new DeviceCallbackService($device, $request);

            $service->handle();
        } catch (\Exception $exception) {
            logger($exception);
        }

        switch ($device) {
            case 'dingding':
                return response([
                    'errNO' => 200
                ]);
                break;
            case 'beefind':
            case 'beefind_device':
                return response([
                    'result' => 1
                ]);
        }

        return $this->responseSuccess();
    }
}
