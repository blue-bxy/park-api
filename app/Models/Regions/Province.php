<?php

namespace App\Models\Regions;

use App\Models\EloquentModel;

class Province extends EloquentModel
{
    protected $fillable = [
        'name', 'province_id'
    ];

    public function city()
    {
        return $this->hasMany(City::class, 'province_id', 'province_id');
    }

    public function country()
    {
        return $this->hasManyThrough(Country::class, City::class, 'province_id', 'city_id', 'province_id', 'city_id');
    }
}
