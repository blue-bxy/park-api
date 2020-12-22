<?php

namespace App\Imports\Admin;

use App\Models\Parks\ParkBluetooth;

class ParkBluetoothImport extends ParkDeviceImport
{
    public function __construct(ParkBluetooth $lock) {
        parent::__construct($lock);
    }

    protected function map(array $data): array {
        $devices = array();
        $index = 0;
        foreach ($data as $datum) {
            if ($datum['蓝牙编号']) {
                $devices[$index++] = [
                    'number' => $datum['蓝牙编号'],
                    'brand_name' => $datum['品牌'],
                    'model_name' => $datum['型号'],
                    'ip' => $datum['IP地址'],
                    'protocol' => $datum['通信协议'],
                    'gateway' => $datum['网关'],
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
