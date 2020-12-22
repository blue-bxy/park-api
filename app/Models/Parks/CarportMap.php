<?php

namespace App\Models\Parks;

use App\Models\EloquentModel;

class CarportMap extends EloquentModel
{
    protected $fillable = [
        'park_id', 'map_id', 'map_key'
    ];

    public function park()
    {
        return $this->belongsTo(Park::class);
    }
}
