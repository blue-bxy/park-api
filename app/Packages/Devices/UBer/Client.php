<?php


namespace App\Packages\Devices\UBer;


use App\Packages\Devices\BaseClient;
use Psr\Http\Message\RequestInterface;

class Client extends BaseClient
{
    protected $base_uri = "http://ahuber.top/iotparklock/manager/iotplate/cust/";

    protected $queryName = 'custKey';

    // 解锁
    public function unlock(...$lockids)
    {

        $lockids = implode('-', $lockids);


        $query = [
            'lockerIds' => $lockids
        ];

        return $this->httpPost('cust-open-comm!unlock.do', [], $query);
    }

    /**
     * 强制上锁
     *
     * @param mixed ...$lockids
     * @return array
     */
    public function forcelock(...$lockids)
    {
        $lockids = implode('-', $lockids);

        $query = [
            'lockerIds' => $lockids
        ];

        return $this->httpPost('cust-open-comm!forcelock.do', [], $query);
    }

    // 其他信息
    public function otherinfo(...$lockids)
    {
        $lockids = implode('-', $lockids);

        $query = [
            'lockerIds' => $lockids
        ];

        return $this->httpPost('cust-open-comm!otherinfo.do', [], $query);
    }

    // 二进制状态
    public function state(...$lockids)
    {
        $lockids = implode('-', $lockids);

        $query = [
            'custKey' => 'ltkj8083001',
            'lockerIds' => $lockids
        ];

        return $this->httpPost('cust-open-comm!selecState.do', [], $query);
    }

    // 十进制状态
    public function decimalState(...$lockids)
    {
        $lockids = implode('-', $lockids);

        $query = [
            'lockerIds' => $lockids
        ];

        return $this->httpPost('cust-open-comm!selecState2.do', [], $query);
    }

    protected function getCredentials()
    {
        return [
            'custKey' => $this->app['config']['client_key']
        ];
    }
}
