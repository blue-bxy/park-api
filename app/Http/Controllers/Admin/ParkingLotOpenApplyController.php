<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ParkingLotOpenApplyExport;
use App\Http\Controllers\Controller;
use App\Http\Resources\ParkingLotApplyResource;
use App\Models\Parks\ParkingLotOpenApply;
use Illuminate\Http\Request;

class ParkingLotOpenApplyController extends BaseController
{
    public function index(Request $request)
    {
        $query = ParkingLotOpenApply::query();

        $query->search($request);

        $applies = $query->latest()->paginate();

        return ParkingLotApplyResource::collection($applies)->additional([
            'status' => ParkingLotOpenApply::$statusMaps
        ]);
    }

    public function update(Request $request, $id)
    {
        $admin = $request->user();

        $request->validate([
            'status' => 'required|string|in:finished,processed'
        ]);

        $apply = ParkingLotOpenApply::find($id);

        $status = $request->input('status');

        $apply->status = $status;

        $apply->{$status.'_at'} = now();

        $apply->admin()->associate($admin);

        $apply->save();

        return $this->responseSuccess();
    }

    public function process(Request $request, $id)
    {
        $apply = ParkingLotOpenApply::find($id);

        $apply->status = ParkingLotOpenApply::STATUS_PROCESSED;
        $apply->processed_at = now();

        $apply->save();

        return $this->responseSuccess();
    }

    public function destroy($id)
    {
        //
    }

    public function export(Request $request)
    {
        return (new ParkingLotOpenApplyExport($request))->download('申请开通小区-'.time().'.xlsx');
    }
}
