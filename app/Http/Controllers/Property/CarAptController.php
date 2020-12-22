<?php

namespace App\Http\Controllers\Property;

use App\Http\Controllers\App\BaseController;
use App\Http\Resources\Property\CarAptResource;
use App\Http\Resources\Property\ParkIncomeResource;
use App\Models\Dmanger\CarApt;
use App\Models\Dmanger\CarAptOrder;
use App\Models\Parks\Park;
use App\Models\Property;
use App\Models\Users\UserOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CarAptController extends BaseController
{

    public function index(Request $request)
    {
        // 查询
        $query = CarApt::query();

        $query->with('orders', 'parkSpace.spaceType','user','userCar', 'parks', 'carRent');

        $query->addSelect([
            'order_no' => UserOrder::select('order_no')
                ->limit(1)
                ->whereColumn('user_orders.id', 'car_apts.user_order_id')
        ]);

        $park_id = ($request->user())->park_id;

        $query->where('park_id',$park_id);

        // 正常结算详情跳转
        if($time = $request->input('time')){

            $query->whereDate('created_at',$time);
        }

        $per_page = $request->input('per_page');

        $data = $query->search($request)->orderBy('id','desc')->paginate($per_page);

        return CarAptResource::collection($data);
    }

    /**
     * 报表生成
     *
     * @param Request $request
     */
    public function export(Request $request)
    {
        return (new \App\Exports\Property\CarApt($request))->download('预约数据表.xlsx');
    }

    /**
     * 查看明细
     *
     * @param $id
     */
    public function show($id)
    {
        $query = UserOrder::query();

        $query->where('car_apt_id',$id);

        $query->with(['parks','carStop','carApts','car']);

        $parkIncome = $query->paginate();

        return ParkIncomeResource::collection($parkIncome);
    }
}
