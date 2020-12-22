<?php

namespace App\Http\Controllers\Property;

use App\Exports\Property\WithdrawalAptOrder;
use App\Http\Resources\Property\AptOrderResource;
use App\Models\Dmanger\CarAptOrder;
use App\Models\Financial\Withdrawal;
use App\Models\Parks\Park;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AptOrderDetailController extends BaseController
{
    /**
     * 预约余额明细
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', $this->per_page);
        $property=$request->user();
        $park_id=$property->park_id;
        $query=Withdrawal::query();
        $query->where('park_id',$park_id);
        $withdrawal=$query->latest()->first('apply_time');
        $end=$withdrawal['apply_time'];
        $map=[];
        if(!empty($end)){
            $map[]=['finished_at','>',$end];
        }else{
            $map[]=['finished_at','<=',now()];
        }
        $query=CarAptOrder::query();
        $query->join('car_apts',function ($join) use ($query,$park_id){
           $join->on('car_apt_orders.car_apt_id','=','car_apts.id');
           $query->where('car_apts.park_id',$park_id);
        });

        if($time=$request->input('time')){
            //预约余额管理-查看详情传递至此的time
            $start_time=Carbon::parse($time)->startOfDay();
            $end_time=Carbon::parse($time)->endOfDay();
            $query->whereBetween('finished_at',[$start_time,$end_time]);
        }else{
            $query->where($map);
        }
        $query->search($request);
        $data=$query->paginate($perPage);
        return AptOrderResource::collection($data);
    }

    /**
     * 报表下载
     * @param Request $request
     */
    public function export(Request $request)
    {
        return (new \App\Exports\Property\WithdrawalAptOrder($request))->download('预约余额明细数据.xlsx');
    }
}
