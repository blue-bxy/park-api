<?php


namespace App\Packages\Devices\DingDing;


class HubMacClient extends Client
{
    /**
     * 设置hubMac名称
     *
     * @param string $mac
     * @param string $name
     * @return array
     */
    public function setName(string $mac, string $name)
    {
        return $this->httpGet('setHubMacName', [
            'hubMac' => $mac,
            'hubMacName' => $name
        ]);
    }

    /**
     * 查询Hub各项信息
     *
     * @param string $mac
     * @param string $oper
     * @return array
     */
    public function get(string $mac, $oper = 'search')
    {
        return $this->httpGet('operNUSLockHubMac', [
            'hubMac' => $mac,
            'oper' => $oper
        ]);
    }

    /**
     * 开启Hub通知
     *
     * @param string $mac
     * @return array
     */
    public function notice(string $mac)
    {
        return $this->httpGet('notiHub', [
            'hubMac' => $mac
        ]);
    }

    /**
     * 设置断网n秒后唤醒
     *
     * @param string $mac
     * @param int $delay
     * @return array
     */
    public function rouseDelay(string $mac, int $delay = 60)
    {
        $delay = str_pad($delay, 6, 0, STR_PAD_LEFT);

        $operation = 'HUB_WT'.$delay;

        return $this->device($mac, $operation);
    }

    /**
     * 重启Hub
     *
     * @param string $mac
     * @return array
     */
    public function reboot(string $mac)
    {
        return $this->device($mac, 'hubReset');
    }

    /**
     * 重启sim卡模组
     *
     * @param string $mac
     * @return array
     */
    public function resmodule(string $mac)
    {
        return $this->device($mac, 'hubResmodule');
    }

    /**
     * 强制重启
     *
     * @param string $mac
     * @return array
     */
    public function restart(string $mac)
    {
        return $this->device($mac, 'hubForceRestart');
    }

    /**
     * 设备休眠
     *
     * @param string $mac
     * @return array
     */
    public function sleep(string $mac)
    {
        return $this->device($mac, 'hubSetSleep');
    }

    /**
     * 设备断电
     *
     * @param string $mac
     * @return array
     */
    public function blackout(string $mac)
    {
        return $this->device($mac, 'hubPowerOff');
    }

    /**
     * Hub学习433
     *
     * @param string $mac
     * @return array
     */
    public function learn(string $mac)
    {
        return $this->device($mac, 'hubLearn433');
    }

    /**
     * device
     *
     * @param string $mac
     * @param string $operation
     * @return array
     */
    public function device(string $mac, string $operation)
    {
        return $this->httpGet('hubOper', [
            'hubMac' => $mac,
            'operation' => $operation
        ]);
    }
}
