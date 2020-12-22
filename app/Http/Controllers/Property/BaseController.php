<?php

namespace App\Http\Controllers\Property;

use App\Http\Controllers\ApiResponse;
use App\Http\Controllers\Controller;

class BaseController extends Controller
{
    use ApiResponse;

    protected $per_page = 15;
}
