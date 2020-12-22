<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Users\UserDevice;
use Illuminate\Http\Request;

class DeviceController extends BaseController
{
    public function jpush(Request $request)
    {
        $request->validate([
            'device_id' => 'required',
            'registration_id' => 'required'
        ]);

        $user = $request->user();

        $res_id = $request->input('registration_id');

        $values = [
            'platform' => $request->header('platform', $request->input('platform')),
            'version' => $request->header('version', $request->input('version')),

            'brand' => $request->input('brand'),
            'model' => $request->input('model'),
            'user_id' => $user ? $user->getKey() : null,
            'uid' => $request->input('device_id')
        ];

        try {
            UserDevice::query()->updateOrCreate([
                'jpush_id' => $res_id,
            ], $values);
        } catch (\Exception $e) {

        }

        return $this->responseSuccess();
    }
}
