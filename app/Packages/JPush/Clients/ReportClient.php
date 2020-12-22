<?php


namespace App\Packages\JPush\Clients;


class ReportClient extends Client
{
    protected $url = "https://report.jpush.cn/v3";

    /**
     * 送达统计详情
     *
     * @param array|string $msg_ids
     * @return array|mixed
     */
    public function received($msg_ids)
    {
        if (is_array($msg_ids)) {
            $msg_ids = implode(',', $msg_ids);
        }

        return $this->httpGet('/received/detail', compact('msg_ids'));
    }

    /**
     * 送达状态查询
     *
     * @param string|int $msg_id
     * @param array $registration_ids
     * @param \DateTimeInterface|string|null $date
     * @return array|mixed
     */
    public function status($msg_id, array $registration_ids, $date = null)
    {
        if ($date instanceof \DateTimeInterface) {
            $date = $date->format('Y-m-d');
        }

        $data = array_filter([
            'msg_id' => $msg_id,
            'registration_ids' => $registration_ids,
            'date' => $date
        ]);

        return $this->httpPost('/status/message', $data);
    }

    /**
     * 消息统计详情
     *
     * @param array|string $msg_ids
     * @return array|mixed
     */
    public function messages($msg_ids)
    {
        if (is_array($msg_ids)) {
            $msg_ids = implode(',', $msg_ids);
        }

        return $this->httpGet('/messages/detail', compact('msg_ids'));
    }

    /**
     * users
     *
     * @param string $time_unit
     * @param string $start
     * @param int $duration
     * @return array|mixed
     */
    public function users(string $time_unit, string $start, int $duration)
    {
        $time_unit = strtoupper($time_unit);

        return $this->httpGet('/users', compact('time_unit', 'start', 'duration'));
    }

    /**
     * 小时
     *
     * @param \DateTimeInterface|string $start
     * @param int $duration
     * @return array|mixed
     */
    public function hourUser($start, int $duration)
    {
        if ($start instanceof \DateTimeInterface) {
            $start = $start->format('Y-m-d H');
        }

        return $this->users('HOUR', $start, $duration);
    }

    /**
     * 天
     *
     * @param \DateTimeInterface|string $start
     * @param int $duration
     * @return array|mixed
     */
    public function dayUser($start, int $duration)
    {
        if ($start instanceof \DateTimeInterface) {
            $start = $start->format('Y-m-d');
        }

        return $this->users('DAY', $start, $duration);
    }

    /**
     * 月
     *
     * @param \DateTimeInterface|string $start
     * @param int $duration
     * @return array|mixed
     */
    public function monthUser($start, int $duration)
    {
        if ($start instanceof \DateTimeInterface) {
            $start = $start->format('Y-m');
        }

        return $this->users('MONTH', $start, $duration);
    }


    public function baseUrl()
    {
        return $this->url;
    }
}
