<?php

namespace App\Models\Parks;

use App\Models\EloquentModel;
use Illuminate\Support\Facades\Cache;

/**
 * Class ParkSetting
 * @package App\Models\Parks
 *
 * @property string $request_url
 * @property string $callback_url
 * @property string $map_id
 * @property string $map_key
 * @property int $park_id
 * @property array $params
 */
class ParkSetting extends EloquentModel
{
    protected $fillable = [
        'park_id', 'map_id', 'map_key', 'request_url', 'callback_url', 'params'
    ];

    protected $casts = [
        'params' => 'array'
    ];

    protected static function boot()
    {
        parent::boot();

        static::saved(function ($setting) {
            $setting->refreshCache();
        });
    }

    public function refreshCache()
    {
        Cache::forget("parking_setting:{$this->park_id}");
    }
}
