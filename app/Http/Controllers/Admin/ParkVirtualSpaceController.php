<?php

namespace App\Http\Controllers\Admin;

use App\Http\Resources\Admin\ParkVirtualSpaceResource;
use App\Models\Parks\Park;
use App\Models\Parks\ParkSpace;
use App\Models\Parks\ParkVirtualSpace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ParkVirtualSpaceController extends BaseController
{
    /**
     * 车位列表
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request) {
        $spaces = ParkVirtualSpace::query()
            ->where('park_area_id', '=', $request->input('park_area_id'))
            ->paginate($request->input('per_page'));
        return ParkVirtualSpaceResource::collection($spaces);
    }

    public function init(Request $request) {
        $park = Park::query()->with('areas')->find($request->input('park_id'));
        if (empty($park)) {
            return $this->responseNotFound();
        }
        $app = app('device.bee_find');
        $res = $app->basic->carport($park->unique_code);
        $data = $res['data'];
        $numbers = array();
        foreach ($data as $floor) {
            $numbers = array_merge($numbers, array_column($floor['carports'], 'carportName'));
        }
        $spaces = ParkSpace::query()->where('park_id', '=', $park->id)
            ->whereIn('number', $numbers)->get();
        $virtualSpaces = array();
        foreach ($data as $floor) {
            foreach ($floor['carports'] as $carport) {
                $space = $spaces->first(function ($item) use ($carport) {
                     return $item->number == $carport['carportName'];
                });
                $virtualSpaces[] = [
                    'code' => $carport['carportCode'],
                    'number' => $carport['carportName'],
                    'floor' => $floor['floorName'],
                    'park_space_id' => $space->id ?? null,
                    'park_area_id' => $space->park_area_id ?? $park->areas[0]->id,
                    'park_id' => $park->id
                ];
            }
        }
        DB::table('park_virtual_spaces')->insert($virtualSpaces);
        return $virtualSpaces;
    }
}
