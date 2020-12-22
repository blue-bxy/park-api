<?php

namespace App\Imports\Admin;

use App\Models\Parks\ParkCamera;
use Illuminate\Support\Facades\DB;

class ParkCameraImport extends ParkDeviceImport
{
    public function __construct(ParkCamera $camera) {
        parent::__construct($camera);
    }

//    /**
//     * 保存数据
//     * @param array $data
//     * @param array $extra
//     * @return array
//     * @throws \Throwable
//     */
//    public function save(array $data, array $extra) {
//        $data = $this->map($data);
//        if (!empty($data)) {
//            $this->init($extra['park_id']);
//        }
//        $devices = $this->check($data);
//        foreach ($devices as $device) {
//            $device['park_id'] = $extra['park_id'];
//            if ($device['monitor_type'] == ParkCamera::MONITOR_TYPE_ENTRANCE) {
//                $device['park_area_id'] = 0;
//            } else {
//                $device['park_area_id'] = $extra['park_area_id'];
//            }
//            if (!$device['error']) {
//                DB::transaction(function () use ($device) {
//                    $this->device = $this->device->create($device);
//                    $spaces = array();
//                    foreach ($device['spaces'] as $space) {
//                        //筛选车位编号存在的情况（id！=0）
//                        if ($space['id']) {
//                            $spaces[] = [
//                                'park_space_id' => $space['id'],
//                                'device_type' => ParkCamera::class,
//                                'device_id' => $this->device->id
//                            ];
//                        }
//                    }
//                    DB::table('park_space_has_devices')->insert($spaces);
//                });
//            }
//        }
//        return $devices;
//    }

    protected function map(array $data): array {
        $devices = array();
        $index = 0;
        foreach ($data as $datum) {
            if ($datum['摄像头编号']) {
                $devices[$index++] = [
                    'number' => $datum['摄像头编号'],
                    'brand_name' => $datum['品牌'],
                    'model_name' => $datum['型号'],
                    'ip' => $datum['IP地址'],
                    'protocol' => $datum['通信协议'],
                    'gateway' => $datum['网关'],
                    'monitor_type' => $datum['类型'],
                    'remark' => $datum['备注'],
                    'spaces' => array()
                ];
            } else {
                if (!in_array('车位编号', $datum)) {
                    $devices[$index - 1]['spaces'][] = $datum['品牌'];
                }
            }
        }
        return $devices;
    }

}
