<?php

namespace App\Models\Regions;

use App\Models\EloquentModel;

class City extends EloquentModel
{
    protected $fillable = [
        'name', 'city_id', 'province_id'
    ];

    public function country()
    {
        return $this->hasMany(Country::class, 'city_id', 'city_id');
    }
}
