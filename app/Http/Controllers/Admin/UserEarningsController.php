<?php

namespace App\Http\Controllers\Admin;

use App\Exports\Admin\FinancialManageUserEarnings;
use App\Http\Resources\Admin\ParkEarningsResource;
use App\Http\Resources\Admin\ParkIncomeResource;
use App\Http\Resources\Admin\UserEarningsResource;
use App\Models\Bills\OrderAmountDivide;
use App\Models\Users\ParkingSpaceRentalBill;
use App\Models\Users\UserOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class UserEarningsController extends BaseController
{
    /**
     * 用户收益
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
//        $perPage = $request->input('per_page', $this->per_page);
//        $query=ParkingSpaceRentalBill::query();
//        $time=$request->input('time');
//        $start_time=Carbon::parse($time)->startOfDay();
//        $end_time=Carbon::parse($time)->endOfDay();
//        $data=$query
//            ->search($request)
//            ->selectRaw('sum(rental_amount) as income,sum(fee) as fee, user_id,created_at')
//            ->whereBetween('created_at',[$start_time,$end_time])
//            ->where('order_type', UserOrder::class)
//            ->groupBy('user_id')->paginate($perPage);
//        $sum=0;
//        foreach ($data as $value){
//            $fee=number_format($value['fee'] / 100, 2);
//            $sum+=$fee;
//        }
//        return UserEarningsResource::collection($data)->additional([
//            'sum_fee'=>$sum,
//        ]);
        $perPage = $request->input('per_page', $this->per_page);

        $query = OrderAmountDivide::query();

        $query->where('owner_fee','!=',0);

        $query->with('park');

        $data = $query
            ->select('id','park_id')
            ->selectSub("sum(total_amount)", 'amount')
            ->selectSub("sum(platform_fee)",'platform_fee')
            ->selectSub("date_format(created_at, '%Y-%m-%d')", 'date')
            ->groupByRaw("date_format(created_at, '%Y-%m-%d'),park_id")
            ->latest()
            ->paginate($perPage);

        return UserEarningsResource::collection($data);
    }

    /**
     * 导出
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function create(Request $request)
    {
        $excel = DB::transaction(function () use ($request) {
            $filename = "用户收益-数据表";
            activity()->enableLogging(); // 开启记录操作日志
            $file_path = get_excel_file_path($filename);
            (new FinancialManageUserEarnings($request))->store($file_path);
            $excel_size = \Storage::disk('excel')->size($file_path);
            $excel_size = ceil($excel_size/1024);
            $model = new \App\Models\ExcelExport([
                'excel_name'        => $filename,
                'excel_size'        => $excel_size,
                'excel_src'         => $file_path,
                'create_excel_time' => now()
            ]);
            $model->save();
            return $model;
        });
        if($excel){
            return $this->responseData('','报表生成成功！','0');
        }else{
            return $this->responseData('','报表生成失败！','1');
        }
    }

    /**
     * 详情
     * @param $user_id
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function show($user_id,Request $request)
    {
        $perPage = $request->input('per_page', $this->per_page);
        $time=$request->input('time');
        $start_time=Carbon::parse($time)->startOfDay();
        $end_time=Carbon::parse($time)->endOfDay();
        $order_id=ParkingSpaceRentalBill::where('user_id',$user_id)
            ->whereBetween('created_at',[$start_time,$end_time])
            ->where('order_type', UserOrder::class)->get('order_id');
        $order_id_arr=array();
        foreach ($order_id as $value){
            array_push($order_id_arr,$value['order_id']);
        }
        $query=UserOrder::query();
        $data=$query->with(['parks','carStop','carApts','car'])
            ->whereIn('id',$order_id_arr)
            ->paginate($perPage);
        return ParkIncomeResource::collection($data);
    }
}
