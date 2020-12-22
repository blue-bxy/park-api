<?php


namespace App\Packages\Devices\DingDing;


class ParkLockClient extends Client
{
    /**
     * 连接
     *
     * @param string $mac
     * @param $lock_mac
     * @return array
     */
    public function conn(string $mac, $lock_mac)
    {
        return $this->device($mac, $lock_mac, 'conn');
    }

    /**
     * 升起
     *
     * @param string $mac
     * @param $lock_mac
     * @return array
     */
    public function rise(string $mac, $lock_mac)
    {
        return $this->device($mac, $lock_mac, 'rise');
    }

    /**
     * 降下
     *
     * @param string $mac
     * @param $lock_mac
     * @return array
     */
    public function drop(string $mac, $lock_mac)
    {
        return $this->device($mac, $lock_mac, 'drop');
    }

    /**
     * 降下（自动升起）
     *
     * @param $mac
     * @param $lock_mac
     * @return array
     */
    public function auto(string $mac, $lock_mac)
    {
        return $this->device($mac, $lock_mac, 'auto');
    }

    /**
     * 连接-升-断开
     *
     * @param $mac
     * @param $lock_mac
     * @return array
     */
    public function dircRise(string $mac, $lock_mac)
    {
        return $this->device($mac, $lock_mac, 'DIRC_RISE');
    }

    /**
     * 连接-降-断开（车走后，地锁不会自动升起）
     *
     * @param string $mac
     * @param $lock_mac
     * @return array
     */
    public function dircDown(string $mac, $lock_mac)
    {
        return $this->device($mac, $lock_mac, 'DIRC_DOWN');
    }

    /**
     * 连接-降-断开（车走后，地锁会自动升起）
     *
     * @param string $mac
     * @param $lock_mac
     * @return array
     */
    public function dircAuto(string $mac, $lock_mac)
    {
        return $this->device($mac, $lock_mac, 'DIRC_AUTO');
    }

    /**
     * 查询
     *
     * @param string $mac
     * @param $lock_mac
     * @return array
     */
    public function read(string $mac, $lock_mac)
    {
        return $this->device($mac, $lock_mac, 'read');
    }

    /**
     * 断开
     *
     * @param string $mac
     * @param $lock_mac
     * @return array
     */
    public function disc(string $mac, $lock_mac)
    {
        return $this->device($mac, $lock_mac, 'disc');
    }

    /**
     * 扫描
     *
     * @param string $mac
     * @param $lock_mac
     * @return array
     */
    public function scan(string $mac, $lock_mac)
    {
        return $this->device($mac, $lock_mac, 'scan');
    }

    /**
     * device
     *string
     * @param string $mac
     * @param $lock_mac
     * @param $type
     * @return array
     */
    public function device(string $mac, $lock_mac, $type)
    {
        return $this->httpGet('operNUSLock', [
            'hubMac' => $mac,
            'lockMac' => strtoupper($lock_mac),
            'operType' => $type,
        ]);
    }
}
