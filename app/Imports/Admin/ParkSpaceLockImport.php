<?php

namespace App\Imports\Admin;

use App\Models\Parks\ParkSpaceLock;

class ParkSpaceLockImport extends ParkDeviceImport
{
    public function __construct(ParkSpaceLock $lock) {
        parent::__construct($lock);
    }

    public function map(array $data): array {
        $devices = array();
        foreach ($data as $datum) {
            $devices[] = [
                'number' => $datum['地锁编号'],
                'brand_name' => $datum['品牌'],
                'model_name' => $datum['型号'],
                'park_space_number' => $datum['车位编号'],
                'ip' => $datum['IP地址'],
                'protocol' => $datum['通信协议'],
                'gateway' => $datum['网关'],
                'remark' => $datum['备注'],
                'spaces' => array()
            ];
        }
        return $devices;
    }

    protected function space(array &$device) {
        $device['park_space_id'] = 0;
        foreach ($this->spaces as $space) {
            if ($device['park_space_number'] == $space['number']) {
                $device['park_space_id'] = $space['id'];
                break;
            }
        }
    }
}
