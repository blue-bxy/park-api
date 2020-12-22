<?php


namespace App\Packages\Map;


class MapServer extends BaseClient
{
    protected $offset = 20;

    public function keyword($keyword, $page = 1)
    {
        return $this->client()->get('place/text?parameters', [
            'keywords' => $keyword,
            // 'types',
            'city' => $this->getCityAdCode(),
            'citylimit' => true,
            'offset' => $this->offset,
            'page' => $page,
            'extensions' => 'all'
        ])->json();
    }

    protected function getCityAdCode($ip = null)
    {
        $city = $this->ip($ip);

        if (isset($city['adcode']) && $adcode = $city['adcode']) {
            return $adcode;
        }

        return '';
    }

    public function ip($ip = null)
    {
        $ip = $ip ?? request()->ip();

        return $this->client()->get('ip?parameters', [
            'ip' => $ip,
            'key' => $this->client_id,
            'sig' => $this->secret,
            'output' => $this->output,
        ])->json();
    }
}
