<?php

namespace App\Http\Controllers\Property;

use App\Http\Resources\Admin\WithdrawalResource;
use App\Models\Financial\Withdrawal;
use App\Models\Parks\Park;
use Illuminate\Http\Request;

    class WithdrawalRecordsController extends BaseController
{
    /**
     * 提现记录
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', $this->per_page);
        $property=$request->user();
        $park_id=$property->park_id;
        $query = Withdrawal::query();

        $query->orderBy('apply_time','desc');
        $data = $query->search($request)
            ->with('park', 'user', 'reviewer')
            ->where('person_type',1)
            ->where('park_id',$park_id)
            ->paginate($perPage);

        return WithdrawalResource::collection($data)->additional([
            'status' => Withdrawal::$statusMap,
            'personTypes' => Withdrawal::$personTypes
        ]);
    }
}
