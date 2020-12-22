<?php

namespace App\Http\Controllers\Property;

use App\Http\Controllers\App\BaseController;
use App\Http\Resources\Property\CarStopResource;
use App\Models\Dmanger\CarAptOrder;
use App\Models\Dmanger\CarStop;
use App\Models\Parks\Park;
use App\Models\Property;
use Illuminate\Http\Request;

class CarStopController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // 满足查询条件进行查询
        $query = CarStop::query();

        $query->with(['park','userOrder','userOrder.carApts.aptOrder','userCar']);

        $park_id = ($request->user())->park_id;

        $query->where('park_id',$park_id);

        $per_page = $request->input('per_page');

        $carStop = $query->search($request)->orderBy('id','desc')->paginate($per_page);

        // 判断查询的结果，如果没有数据就提示查询条件不匹配
        if(!($carStop->toArray())['data']){
            return $this->responseNotFound('未找到数据，请重新输入查询条件',40007);
        }

        return CarStopResource::collection($carStop);
    }

    /**
     * 报表导出
     * @param Request $request
     */
    public function export(Request $request)
    {
        return (new \App\Exports\Property\CarStop($request))->download('停车数据.xlsx');
    }
}
