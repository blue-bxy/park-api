<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Users\UserCar;
use Illuminate\Http\Request;

class UserCarVerifyController extends BaseController
{
    public function verify(Request $request, UserCar $car = null)
    {
        /** @var User $user */
        $user = $request->user();

        $request->validate([
            'owner_name' => 'required|string',
            'car_number' => 'required',
            // 'frame_number' => 'required',
            // 'engine_number' => 'required',
            // 'brand_model' => 'required',
        ], [
            'owner_name.required' => '车主姓名不能为空',
            'car_number.required' => '车牌号不能为空',
            // 'frame_number.required' => '车架号'
        ]);

        $car = $user->cars()->updateOrCreate([
            'car_number' => $request->input('car_number')
        ], [
            'owner_name' => $request->input('owner_name'),
            'frame_number' => $request->input('frame_number'),
            'engine_number' => $request->input('engine_number'),
            'brand_model' => $request->input('brand_model'),
        ]);

        return $this->responseData([
            'car_id' => $car->id
        ]);
    }

    /**
     * 行驶证图像识别
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function upload(Request $request)
    {
        $request->validate(['img' => 'required|image']);

        $img = $request->file('img');

        $side = $request->input('side', 'face');

        try {
            $result = app('ocr')->license($img, $side);

            $data = [
                'owner_name' => $result['owner'],
                'car_number' => $result['plate_num'],
                'frame_number' => $result['vin'],
                'engine_number' => $result['engine_num'],
                'brand_model' => $result['model'],
            ];

            return $this->responseData($data);
        } catch (\Exception $exception) {
            return $this->responseFailed('识别失败，请重试', 40024);
        }
    }
}
