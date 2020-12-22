<?php

namespace App\Http\Controllers\Property;

use App\Exports\Property\ParkIncome;
use App\Http\Controllers\App\BaseController;
use App\Http\Resources\Property\ParkIncomeResource;
use App\Models\Parks\Park;
use App\Models\Property;
use App\Models\Users\UserOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ParkIncomeController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // 满足查询条件进行查询
        $query = UserOrder::query();

        $query->with(['user','parks','carStop','carApts','carApts.orders','car','refund']);

        $per_page = $request->input('per_page');

        $park_id = ($request->user())->park_id;

        // 退款明细
        if($order_id = $request->input('order_id')){
            $query->where('id',$order_id);
        }

        $query->where(['park_id'=>$park_id,'status'=>'refunded']);

        $parkIncome = $query->search($request)->orderby('id','desc')->paginate($per_page);

//        return $this->responseData($parkIncome);

        return ParkIncomeResource::collection($parkIncome);

    }

    /**
     * 报表导出
     *
     * @return \Illuminate\Http\Response
     */
    public function export(Request $request)
    {
        $excel = DB::transaction(function () use ($request) {
            activity()->enableLogging(); // 开启记录操作日志

            // 获取当前用户的停车场名称
            $admin_id = ($request->user())->id;

            $park = Park::where('property_id',$admin_id)->first();

            $park_name = $park->project_name;

            $filename = $park_name ."数据查询-总数据表";

            $file_path = get_excel_file_path($filename);

            // 以下为即时导出，队列导出写法不同
            (new ParkIncome($request))->store($file_path);

            $excel_size = \Storage::disk('excel')->size($file_path);

            // 将字节转化成kb
            $excel_size = ceil($excel_size/1024);

            $model = new \App\Models\ExcelExport([
                'excel_name' => $filename,
                'excel_size' => $excel_size,
                'excel_src' => $file_path,
                'create_excel_time' => now()
            ]);

            $model->save();
            return $model;
        });
        if($excel){
            return $this->responseSuccess();
        }else{
            return $this->responseFailed('报表生成失败！','4007');
        }
    }
}
