<?php

namespace App\Models\Parks;

use App\Models\EloquentModel;

class ParkServiceCallback extends EloquentModel
{
    protected $fillable = [
        'park_id', 'url', 'params', 'result'
    ];

    protected $casts = [
        'params' => 'array',
        'result' => 'array',
    ];
}
