<?php


namespace App\Packages\Devices\BeeFind;


class BasicClient extends Client
{
    protected $base_uri = "https://www.beefindtech.com:8443/SttingParam/platform/dock/";

    /**
     * 数据同步初始化
     *
     * @param $park_id
     * @return array
     */
    public function carport($park_id)
    {
        return $this->httpGet('basics/carport', [
            'pmParkId' => $park_id
        ]);
    }

    /**
     * 地锁数据
     *
     * @param $park_id
     * @return array
     */
    public function parkLock($park_id)
    {
        return $this->device($park_id, '01');
    }

    /**
     * 摄像头数据
     *
     * @param $park_id
     * @return array
     */
    public function camera($park_id)
    {
        return $this->device($park_id, '02');
    }

    /**
     * 车位指示灯数据
     *
     * @param $park_id
     * @return array
     */
    public function pilotLamp($park_id)
    {
        return $this->device($park_id, '03');
    }

    /**
     * 定位蓝牙数据
     *
     * @param $park_id
     * @return array
     */
    public function bluetooth($park_id)
    {
        return $this->device($park_id, '04');
    }

    /**
     * 获取设备基础数据
     *
     * @param $park_id
     * @param $type
     * @return array
     */
    public function device($park_id, $type)
    {
        return $this->httpGet('basics/device', [
            'pmParkId' => $park_id,
            'deviceType' => $type
        ]);
    }
}
