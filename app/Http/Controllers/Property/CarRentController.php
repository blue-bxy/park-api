<?php

namespace App\Http\Controllers\Property;

use App\Http\Controllers\App\BaseController;
use App\Http\Resources\Property\CarRentResource;
use App\Models\Dmanger\CarRent;
use App\Models\Parks\Park;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CarRentController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = CarRent::query();

        $query->with('parks', 'orders','user','rentals');

        $park_id = ($request->user())->park_id;

        $query->where('park_id',$park_id);

        $query->where('user_type','App\Models\User');

        $per_page = $request->input('per_page');

        $car_rent = $query->search($request)->orderBy('id','desc')->paginate($per_page);

        return CarRentResource::collection($car_rent);
    }

    /**
     * 报表生成
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function export(Request $request)
    {
        return (new \App\Exports\Property\CarRent($request))->download('出租数据.xlsx');
    }
}
