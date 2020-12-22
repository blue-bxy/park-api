<?php

namespace App\Models\Regions;

use App\Models\EloquentModel;

class Country extends EloquentModel
{
    protected $fillable = [
        'name', 'city_id', 'country_id'
    ];
}
