<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin;
use App\Models\Property;
use Illuminate\Http\Request;

class PasswordResetController extends BaseController
{
    public function reset(Request $request, Admin $admin)
    {
        $admin->reset($request);

        return $this->responseSuccess();
    }

    public function resetPropertiesPwd(Request $request, Property $property)
    {
        $property->reset($request);

        return $this->responseSuccess();
    }
}
