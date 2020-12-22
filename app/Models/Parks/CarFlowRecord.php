<?php

namespace App\Models\Parks;

use App\Models\EloquentModel;

class CarFlowRecord extends EloquentModel
{
    protected $fillable = [
        'park_id', 'code', 'result', 'type'
    ];

    protected $casts = [
        'result' => 'array'
    ];
}
