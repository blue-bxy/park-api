<?php

namespace App\Models\Traits;

use App\Models\Parks\CarportMap;
use App\Models\Parks\Park;

trait HasMap
{
    /**
     * map
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOneThrough
     */
    public function map()
    {
        return $this->hasOneThrough(CarportMap::class, Park::class,
            'id', 'park_id', 'park_id'
        )->withDefault([
            'map_id' => '',
            'map_key' => ''
        ]);
    }
}
