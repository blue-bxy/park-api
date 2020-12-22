<?php

namespace App\Packages\Devices\BeeFind;


class DeviceClient extends Client
{
    protected $base_uri = "https://www.51beefind.com/";

    /**
     * 车位锁上锁
     *
     * @param $park_id
     * @param $device_id
     * @return array
     */
    public function lock($park_id, $device_id)
    {
        return $this->control($park_id, $device_id, 1, 01);
    }

    /**
     * 车位锁解锁
     *
     * @param $park_id
     * @param $device_id
     * @return array
     */
    public function unlock($park_id, $device_id)
    {
        return $this->control($park_id, $device_id, 2, 01);
    }

    /**
     * device
     *
     * @param $park_id
     * @param $device_id
     * @param $status
     * @param $type
     * @return array
     */
    public function control($park_id, $device_id, $status, $type)
    {
        return $this->httpPost('beefindDevices/control', [], [
            'pmParkId' => $park_id,
            'deviceId' => $device_id,
            'deviceType' => $type,
            'data' => [
                'runStatus' => $status
            ]
        ]);
    }

    /**
     * 车位锁
     *
     * @param $park_id
     * @return array
     */
    public function parkLock($park_id)
    {
        return $this->device($park_id, 01);
    }

    /**
     * 摄像头
     *
     * @param $park_id
     * @return array
     */
    public function camera($park_id)
    {
        return $this->device($park_id, 02);
    }

    /**
     * 车场硬件设备查询
     *
     * @param $park_id
     * @param $type
     * @param null $device_id
     * @return array
     */
    public function device($park_id, $type, $device_id = null)
    {
        return $this->httpGet('beefindDevices', [
            'pmParkId' => $park_id,
            'deviceId' => $device_id,
            'deviceType' => $type,
        ]);
    }
}
