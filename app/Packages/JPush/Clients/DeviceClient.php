<?php


namespace App\Packages\JPush\Clients;


class DeviceClient extends Client
{
    protected $url = "https://device.jpush.cn/v3";

    public function baseUrl()
    {
        return $this->url;
    }

    /**
     * 查询设备的别名与标签
     *
     * @param $registration_id
     * @return array|mixed
     */
    public function devices($registration_id)
    {
        return $this->httpGet("/devices/{$registration_id}");
    }

    /**
     * 设置设备的别名与标签
     *
     * @param $registration_id
     * @param array $data
     * @return array|mixed
     */
    public function updateDevice($registration_id, array $data)
    {
        return $this->httpPost("/devices/{$registration_id}", $data);
    }

    /**
     * addTags
     *
     * @param $registration_id
     * @param $tags
     * @return array|mixed
     */
    public function addTags($registration_id, $tags)
    {
        $data = [
            'add' => is_array($tags) ? $tags : [$tags]
        ];

        return $this->updateDevice($registration_id, [
            'tags' => $data
        ]);
    }

    /**
     * removeTags
     *
     * @param $registration_id
     * @param $tags
     * @return array|mixed
     */
    public function removeTags($registration_id, $tags)
    {
        $data = [
            'remove' => is_array($tags) ? $tags : [$tags]
        ];

        return $this->updateDevice($registration_id, [
            'tags' => $data
        ]);
    }

    /**
     * updateAlias
     *
     * @param string $registration_id
     * @param string $alias
     * @return array|mixed
     */
    public function updateAlias(string $registration_id, string $alias)
    {
        return $this->updateDevice($registration_id, [
            'alias' => $alias
        ]);
    }

    /**
     * updateMobile
     *
     * @param $registration_id
     * @param $mobile
     * @return array|mixed
     */
    public function updateMobile($registration_id, $mobile)
    {
        return $this->updateDevice($registration_id, [
            'mobile' => $mobile
        ]);
    }

    /**
     * clearMobile
     *
     * @param $registration_id
     * @return array|mixed
     */
    public function clearMobile($registration_id)
    {
        return $this->updateDevice($registration_id, [
            'mobile' => ''
        ]);
    }

    /**
     * clearTags
     *
     * @param $registration_id
     * @return array|mixed
     */
    public function clearTags($registration_id)
    {
        return $this->updateDevice($registration_id, [
            'tags' => ''
        ]);
    }

    /**
     * 查询别名
     *
     * @param $alias
     * @param string|null $platform
     * @return array|mixed
     */
    public function getAlias($alias, string $platform = null)
    {
        $data = array_filter([
            'platform' => $platform
        ]);

        return $this->httpGet("/aliases/{$alias}", $data);
    }

    /**
     * 删除别名
     *
     * @param $alias
     * @param string|null $platform
     * @return array|mixed
     */
    public function deleteAlias($alias, string $platform = null)
    {
        return $this->http()->bodyFormat('query')
            ->delete("/aliases/{$alias}", is_null($platform) ? [] : [
                'platform' => $platform
            ])->json();
    }

    /**
     * 解绑设备与别名的绑定关系
     *
     * @param $alias
     * @param array|string $registration_ids
     * @return array|mixed
     */
    public function removeAlias($alias, $registration_ids)
    {
        $data = [
            'remove' => is_array($registration_ids) ? $registration_ids : [$registration_ids]
        ];

        return $this->httpPost("/aliases/{$alias}", [
            'registration_ids' => $data
        ]);
    }

    /**
     * 标签列表
     *
     * @return array|mixed
     */
    public function tags()
    {
        return $this->httpGet('/tags');
    }

    /**
     * 判断设备与标签绑定关系
     *
     * @param $tag
     * @param $registration_id
     * @return array|mixed
     */
    public function isDeviceInTag($tag, $registration_id)
    {
        return $this->httpGet("/tags/{$tag}/registration_ids/{$registration_id}");
    }

    /**
     * 更新标签
     *
     * @param $tag
     * @param array $data
     * @return array|mixed
     */
    public function updateTags($tag, array $data)
    {
        return $this->httpPost("/tags/{$tag}", [
            'registration_ids' => $data
        ]);
    }

    /**
     * 添加设备标签
     *
     * @param $tag
     * @param $registration_ids
     * @return array|mixed
     */
    public function addDevicesToTag($tag, $registration_ids)
    {
        $data = [
            'add' => is_array($registration_ids) ? $registration_ids : [$registration_ids]
        ];

        return $this->updateTags($tag, $data);
    }

    /**
     * 移除设备标签
     *
     * @param $tag
     * @param $registration_ids
     * @return array|mixed
     */
    public function removeDevicesFromTag($tag, $registration_ids)
    {
        $data = [
            'remove' => is_array($registration_ids) ? $registration_ids : [$registration_ids]
        ];

        return $this->updateTags($tag, $data);
    }

    /**
     * 删除标签
     *
     * @param $tag
     * @param string|null $platform
     * @return array|mixed
     */
    public function deleteTags($tag, string $platform = null)
    {
        return $this->http()->bodyFormat('query')
            ->delete("/tags/{$tag}", is_null($platform) ? [] : [
                'platform' => $platform
            ])->json();
    }

    /**
     * 获取用户在线状态
     *
     * @param mixed ...$registration_ids
     * @return array|mixed
     */
    public function status(...$registration_ids)
    {
        return $this->httpPost('/devices/status', compact('registration_ids'));
    }
}
