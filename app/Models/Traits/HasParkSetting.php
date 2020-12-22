<?php

namespace App\Models\Traits;

use App\Models\Parks\ParkSetting;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Trait HasParkSetting
 * @package App\Models\Traits
 *
 * @property HasOne|ParkSetting $setting
 */
trait HasParkSetting
{
    /**
     * setting
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function setting()
    {
        return $this->hasOne(ParkSetting::class)->withDefault([
            'map_id' => '',
            'map_find_car_url' => '',
            'map_find_parking_url' => '',
            'request_url' => '',
            'callback_url' => '',
            'params' => [],
        ]);
    }

    public function getRequestUrl()
    {
        return $this->setting->request_url;
    }

    public function getCallbackUrl()
    {
        return $this->setting->callback_url;
    }

    public function getFindCarUrl()
    {
        $url = $this->setting->map_find_car_url;

        if (!$url) return '';

        return $this->getUrl(parse_url($url));
    }

    public function getFindParkingUrl()
    {
        $url = $this->setting->map_find_parking_url;

        if (!$url) return '';

        return $this->getUrl(parse_url($url));
    }

    private function getUrl(array $urls)
    {
        $params = $this->getMapParams();

        $url =  sprintf("%s://%s/%s", $urls['scheme'], $urls['host'], $urls['path']);

        if ($url) {
            // 将key 进行驼峰
            $params = array_combine(array_map('camel_case', array_keys($params)), array_values($params));

            $query = http_build_query($params);

            $url .= '?'.$query;
        }

        return $url;
    }

    public function getParams()
    {
        return $this->setting->params;
    }

    public function getMapParams()
    {
        // map_id,floor
        $this->loadMissing('setting');

        $this->loadMissing('areas');

        $default = $this->areas->filter(function ($area) {
            return $area->hasDefault();
        })->first();

        $floor = $default ? $default->floor : 1;

        return [
            'map_id' => $this->setting->map_id,
            'floor_id' => $floor
        ];
    }

    /**
     * 获取实时余位
     *
     * @return array|mixed
     */
    public function getRemain()
    {
        $url = $this->getRequestUrl();

        $params = $this->getParams();

        if (!$url || !$params) {
            return [
                'result' => false,
                'remain' => 0
            ];
        }


        try {
            $response = \Http::post($url, $params);

            $result = true;
            return array_merge(['result' => $result], $response->json());
        } catch (\Exception $exception) {
            //
            $result = false;
        }

        return [
            'result' => $result,
            'remain' => 0
        ];
    }
}
