<?php


namespace App\Services;


use App\Models\DeviceCallback;
use App\Models\Dmanger\CarStop;
use App\Models\Parks\ParkServiceCallback;
use App\Models\Parks\ParkSpace;
use App\Models\Parks\ParkSpaceLock;
use App\Models\Parks\ParkVirtualSpace;
use App\Models\Users\UserCar;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class DeviceCallbackService
{
    protected $device;

    protected $request;

    protected static $beefindTypes = [
        '01' => 'park_lock',
        '02' => 'camera',
        '03' => 'pilot_lamp',
        '04' => 'bluetooth',
    ];

    /**
     * DeviceCallbackService constructor.
     * @param string $device
     * @param Request $request
     */
    public function __construct(string $device, Request $request)
    {
        $this->device = $device;

        $this->request = $request;
    }

    public function handle()
    {
        $data = $this->getData();

        $park_id = null;

        try {
            switch ($this->device) {
                case 'beefind':
                    //车位监控状态改变
                    //{
                    //  	"pmParkId" : " xxxxxxxxxxxxxxxx",
                    // 			"parking" : [
                    // {
                    // "carportCode":"xxxx",
                    // "parkState":"0",
                    // "carNum":"沪A88888",
                    // "timestamp":"2019-11-12 20:30",
                    // "pic":"车位图片地址"
                    // },
                    // ...
                    // ]
                    // }
                    $park_id = $data['pmParkId'];

                    $this->carport($park_id, $data['parking']);

                    break;
                case 'beefind_device':
                    //{
                    //  	"pmParkId" : " xxxxxxxxxxxxxxxx",
                    // "deviceType" : "01",
                    // 			"devices" : [
                    // {
                    // "deviceId":"xxxx",
                    // "state":"0",
                    // "runStatus":"1",
                    // "energy":"20"
                    // },
                    // ]
                    // }
                    //{"deviceType":"03","devices":[{"deviceId":"E5817567","runStatus":"1","state":"0"}],"pmParkId":"B0048"}
                    $park_id = $data['pmParkId'];

                    $deviceType = $data['deviceType'];

                    $type = self::$beefindTypes[$deviceType];

                    $this->device($park_id, $type, $data['devices']);

                    break;
                case 'park_lock':
                    //{"data":"{\"type\":1,\"data\":{\"stateUpdateDate\":1592230304632,\"parkState\":2,\"lockerState\":1,\"electric\":0.0,\"lockerId\":8083002,\"custkey\":\"ltkj8083001\",\"ordercome\":7},\"status\":1}"}
                    // {"data":"{\"type\":1,\"data\":{\"stateUpdateDate\":1592233964568,\"parkState\":2,\"lockerState\":1,\"electric\":4.1366086,\"lockerId\":8083002,\"custkey\":\"ltkj8083001\",\"ordercome\":7},\"status\":1}"}
                    break;
                case 'dingding':
                    //{"hubMac":"DZF92698276517","message":"LIVE_MAST:5.864062V"}
                    // {"hubMac":"DZF92698276517","message":"LIVE_MAST:5.864062V"}
                    // {"hubMac":"DZF92698276517","message":"LIVE_MAST:5.864062V"}
                    // {"hubMac":"DZF92698276517","message":"LIVE_MAST:5.864062V"}
                    // {"hubMac":"DZF92698276517","message":"LIVE_MAST:5.864062V"}
                    // {"hubMac":"DZF92698276517","message":"LIVE_MAST:5.864062V"}
                    // {"hubMac":"DZF92698276517","message":"LIVE_MAST:5.864062V"}
                    // {"hubMac":"DZF92698276517","message":"LIVE_MAST:5.864062V"}
                    // {"hubMac":"DZF92698276517","message":"LIVE_MAST:5.864062V"}
                    break;
            }

        } catch (\Exception $exception) {
            logger($exception);
        }

        DeviceCallback::create([
            'park_number' => $park_id,
            'gateway' => $this->device,
            'result' => $data
        ]);
    }

    public function getData()
    {
        $data = $this->request->all();

        if (is_string($data)) {
            parse_str($data, $data);
        }

        return $data;
    }

    public function carport($park_number, array $data)
    {
        foreach ($data as $stop) {
            /** @var ParkVirtualSpace $space */
            $space = ParkVirtualSpace::query()
                ->with('group')
                ->whereHas('park', function ($query) use ($park_number) {
                    $query->where('unique_code', $park_number);
                })
                ->where('code', $stop['carportCode'])
                ->first();

            if ($space) {
                $is_stop = (bool) $stop['parkState'];

                $car_number = $this->carNum($stop['carNum']);

                $space->handle($is_stop, $stop['pic'], $car_number);
                if ($space->park_space_id) {
                    $item = [
                        'type' => 'carport',
                        'data' => [
                            "park_num" => $park_number,
                            "carport_number" => $space->number,
                            "pic" => $stop['pic'],
                            "has_space" =>  (bool) $is_stop,
                            "car_number" => $car_number,
                        ]
                    ];
                    try {
                        // 停车场推送地址
                        $url = get_park_setting($space->park_id, 'callback_url');

                        $response = \Http::post($url, $item);

                        $result = [
                            'item' => $item,
                            'result' => $response->json()
                        ];

                        logger('发送停车场实时车位状态', $result);

                        ParkServiceCallback::query()->create([
                            'park_id' => $space->park_id ?? null,
                            'url' => $url,
                            'params' => $item,
                            'result' => $response->json()
                        ]);
                    } catch (\Exception $exception) {
                        //
                    }
                }

                // 如果有车且车牌有效，更新停车记录
                // if ($car_number) {
                //     $is_stop ? $space->stay($car_number) : $space->leave($stop);
                // }
            }
        }
    }

    public function device($park_number, $type, array $data)
    {
        $type = camel_case($type);

        if (method_exists($this, $type)) {
            $this->$type($park_number, $data);
        }

    }

    protected function parkLock($park_number, array $data)
    {
        collect($data)->each(function ($device) {
            ParkSpaceLock::query()
                ->where('number', $device['deviceId'])
                ->update([
                    'status' => $device['runStatus'] == 2 ? 0 : $device['runStatus'],
                    'network_status' => $device['state']
                ]);
        });

    }

    protected function carNum($stop_car_number)
    {
        // 省+区代码+至少5位随机码 京A 12345（6位新能源）
        if (mb_strlen($stop_car_number) < 7) {
            return false;
        }

        return format_car_num($stop_car_number);
    }
}
