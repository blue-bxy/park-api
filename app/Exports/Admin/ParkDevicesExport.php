<?php

namespace App\Exports\Admin;

use App\Exports\ExcelExport;
use Illuminate\Http\Request;

class ParkDevicesExport extends ExcelExport
{
    protected $request;

    public function __construct(Request $request) {
        $this->request = $request;
    }
}
