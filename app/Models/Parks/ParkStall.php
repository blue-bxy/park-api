<?php

namespace App\Models\Parks;

use App\Models\EloquentModel;


class ParkStall extends EloquentModel
{
    protected $fillable = [
        'carport_count', 'fixed_carport_count', 'charging_pile_carport',
        'order_carport', 'temporary_carport_count', 'lanes_count', 'free_time',
        'expect_temporary_parking_count', 'park_operation_time',
        'do_business_time', 'fee_string', 'map_fee', 'park_id'
    ];
}
