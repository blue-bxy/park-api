<?php

namespace App\Console\Commands;

use App\Models\DeviceSynchronizeLog;
use App\Models\Parks\Park;
use App\Models\Parks\ParkCamera;
use Illuminate\Console\Command;

class ParkDeviceRefresh extends Command
{
    protected $basicUrl = 'https://www.51beefind.com/beefindDevices';
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'device:refresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '设备信息刷新';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     */
    public function handle()
    {
        $parks = Park::query()->where('unique_code', '<>', '')->get();
        foreach ($parks as $park) {
            $this->cameras($park);
        }
    }

    private function getError($errorCode) {
        switch ($errorCode) {
            case '0': return '正常';
            case '1': return '版本异常';
            case '2': return '掉线';
            default: return '其他故障';
        }
    }

    private function cameras($park) {
        $client = app('device.bee_find');
        $res = $client->device->device($park->unique_code, '02');
        $data = $res['data'] ?? null;
        if (empty($data)) {
            return;
        }

        $arr = array();
        foreach ($data as $item) {
            $arr[$item['deviceId']] = [
                'error' => $this->getError($item['errorCode'])
            ];
        }
        $cameras = ParkCamera::query()->whereIn('number', array_keys($arr))->get();
        foreach ($cameras as $camera) {
            $camera->error = $arr[$camera->number]['error'];
            $camera->save();
        }
        DeviceSynchronizeLog::query()->create([
            'park_number' => $park->unique_code,
            'gateway' => 'bee_find',
            'type' => DeviceSynchronizeLog::TYPE_CAMERA,
            'result' => json_encode($res)
        ]);
    }
}
