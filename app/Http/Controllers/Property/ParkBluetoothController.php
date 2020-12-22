<?php

namespace App\Http\Controllers\Property;

use App\Models\Parks\ParkBluetooth;

class ParkBluetoothController extends ParkDeviceController
{
    public function __construct(ParkBluetooth $bluetooth) {
        parent::__construct($bluetooth);
    }
}
