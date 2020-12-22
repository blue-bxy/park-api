<?php

namespace App\Http\Controllers\Admin;

use App\Exports\Admin\FinancialManageParkEarnings;
use App\Http\Resources\Admin\ParkEarningsResource;
use App\Http\Resources\Admin\ParkIncomeResource;
use App\Models\Bills\OrderAmountDivide;
use App\Models\Financial\ParkingFee;
use App\Models\Users\UserOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ParkEarningsController extends BaseController
{

    /**
     * 车场收益
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {

//        $perPage = $request->input('per_page', $this->per_page);
//        $query=UserOrder::query();
//        $time=$request->input('time');
//        $start_time=Carbon::parse($time)->startOfDay();
//        $end_time=Carbon::parse($time)->endOfDay();
//        $data=$query
//            ->search($request)
//            ->selectRaw('sum(total_amount) as income, park_id,finished_at')
//            ->whereBetween('finished_at',[$start_time,$end_time])
//            ->groupBy('park_id')->paginate($perPage);
//        $sum=0;
//        foreach ($data as $value){
//            $park_fee=ParkingFee::where('park_id',$value['park_id'])->pluck('fee')[0]??null;
//            $income=number_format($value['income'] / 100, 2);
//            $fee=$income*($park_fee/100);
//            $sum+=$fee;
//        }
//        return ParkEarningsResource::collection($data)->additional([
//            'sum_fee' => $sum
//        ]);

        $perPage = $request->input('per_page', $this->per_page);

        $query = OrderAmountDivide::query();

        $query->with('park');

        $data = $query
            ->select('park_id')
            ->selectSub("sum(total_amount)", 'amount')
            ->selectSub("sum(platform_fee)",'platform_fee')
            ->selectSub("date_format(created_at, '%Y-%m-%d')", 'date')
            ->groupByRaw("date_format(created_at, '%Y-%m-%d'),park_id")
            ->latest()
            ->paginate($perPage);

        return ParkEarningsResource::collection($data);
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
            $filename = "车场收益-数据表";
            activity()->enableLogging(); // 开启记录操作日志
            $file_path = get_excel_file_path($filename);
            (new FinancialManageParkEarnings($request))->store($file_path);
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
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function store(Request $request)
    {
        $park_id=$request->input('park_id');
        $time=$request->input('time');
        $start_time=Carbon::parse($time)->startOfDay();
        $end_time=Carbon::parse($time)->endOfDay();
        $perPage = $request->input('per_page', $this->per_page);
        $query=UserOrder::query();
        $data=$query->with(['parks','carStop','carApts','car'])
            ->where('park_id',$park_id)
            ->whereBetween('finished_at',[$start_time,$end_time])
            ->paginate($perPage);
        return ParkIncomeResource::collection($data);
    }
}
