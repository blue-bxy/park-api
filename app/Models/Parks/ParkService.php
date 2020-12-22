<?php

namespace App\Models\Parks;

use App\Models\EloquentModel;

class ParkService extends EloquentModel
{
    protected $fillable = [
        'park_id', 'salesman_number', 'sales_name', 'sales_phone', 'contract_no',
        'activation_code', 'contract_start_period', 'contract_end_period'
    ];

}
