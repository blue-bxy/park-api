<?php


namespace App\Services;


use App\Jobs\CarBeganEntrance;
use App\Jobs\CarDeparture;
use App\Models\Parks\CarFlowRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class ParkCallbackService
{
    public function handle(Request $request, string $code)
    {
        $response = $request->all();

        if (Arr::has($response, ['method', 'data'])) {
            $method = $response['method'];

            if (!method_exists($this, $method)) {
                return;
            }

            $this->$method($code, $response['data']);
        }
    }

    protected function in($code, array $data)
    {
        //{"method":"in","data":{"inTime":1596514060.276461,"plateNo":"沪CA6E86","carType":0,"parkinglotId":"001","pictureIn":"https://jieting-test.oss-cn-shanghai.aliyuncs.com/SHCJTCGLFWYXGS_fbds/2020-8/Day_4/car/15965140603025668.jpg"}}
        $car = $data['plateNo'] ?? '';

        if (!$car) return;

        $result = [
            'code' => $code,
            'time' => $data['inTime'],
            'car_number' => format_car_num($car),
            'pic' => $data['pictureIn'],
            'car_type' => $data['carType'],
            'results' => $data
        ];

        dispatch_now(new CarBeganEntrance($result));

        CarFlowRecord::query()->create([
            'type' => 'in',
            'code' => $code,
            'result' => $data
        ]);
    }

    protected function out($code, array $data)
    {
        //{"method":"out","data":{"outTime":1596516440.002523,"duration":0.67,"plateNo":"沪CA6E86","parkinglotId":"001","pictureOut":"https://jieting-test.oss-cn-shanghai.aliyuncs.com/SHCJTCGLFWYXGS_fbds/2020-8/Day_4/car/1596516440067142.jpg"}}
        $car = $data['plateNo'] ?? '';

        if (!$car) return;

        $result = [
            'code' => $code,
            'time' => $data['outTime'], // 离场时间
            'car_number' => format_car_num($car),
            'pic' => $data['pictureOut'],
            'duration' => $data['duration'], // 停车时长，单位小时
            'results' => $data
        ];

        dispatch_now(new CarDeparture($result));

        CarFlowRecord::query()->create([
            'type' => 'out',
            'code' => $code,
            'result' => $data
        ]);
    }
}
