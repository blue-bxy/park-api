<?php

namespace App\Http\Controllers\Property;

use App\Http\Controllers\App\BaseController;

use App\Http\Resources\Property\UserRefundResource;
use App\Models\Property;
use App\Models\Users\UserRefund;
use Illuminate\Http\Request;

class UserRefundController extends BaseController
{

    public function index(Request $request)
    {
        $park_id = ($request->user())->park_id;

        $query = UserRefund::query();

        $query->whereHasMorph('order','App\Models\Users\UserOrder',function ($query) use ($park_id) {
            $query->where('park_id',$park_id);
        });

        $per_page = $request->input('per_page');

        $user_refund = $query->latest()->paginate($per_page);

        return UserRefundResource::collection($user_refund);
    }
}
