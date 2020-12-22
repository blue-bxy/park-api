<?php

namespace App\Models;

class PaymentGateway extends EloquentModel
{
    protected $fillable = [
        'gateway', 'icon', 'desc', 'gateway_name', 'sort',
        'max_money', 'platform', 'enabled'
    ];

    protected $casts = [
        'platform' => 'array',
        'enabled'  => 'boolean',
    ];

    public function getCoverAttribute()
    {
        $icon = $this->attributes['icon'];

        if (is_null($icon)) {
            return null;
        }

        if (pathinfo($icon, PATHINFO_EXTENSION)) {
            return \Storage::disk('public')->url("static/". $this->icon);
        }

        return $icon;
    }
}
