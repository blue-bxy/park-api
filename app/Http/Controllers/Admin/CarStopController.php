<?php

namespace App\Http\Controllers\Admin;

use App\Exports\DmangerCarStop;
use App\Exports\ExcelExport;
use App\Http\Resources\Admin\CarStopResource;
use App\Models\Dmanger\CarAptOrder;
use App\Models\Dmanger\CarStop;
use App\Models\Parks\Park;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;

/**
 * 停车场的控制器
 * @package App\Http\Controllers\Admin
 */
class CarStopController extends BaseController
{
    /**
     * 停车记录的显示页面
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // 满足查询条件进行查询
        $query = CarStop::query();

        $query->with(['park','userOrder','userOrder.carApts.aptOrder','userCar']);

//        $query->whereHas('userOrder',function($query) use ($request){
//            if ($freeTypeId = $request->input('free_type_id')) {
//                $car_apt_id = CarAptOrder::where('coupon_id',$freeTypeId)->pluck('car_apt_id');
//                $query->whereIn('car_apt_id', $car_apt_id);
//            }
//        });

        $per_page = $request->input('per_page');

        $carStop = $query->search($request)->orderBy('id','desc')->paginate($per_page);

        // 判断查询的结果，如果没有数据就提示查询条件不匹配
        if(!($carStop->toArray())['data']){
            return $this->responseNotFound('未找到数据，请重新输入查询条件',40007);
        }

        return CarStopResource::collection($carStop);
    }

    /**
     *生成报表，并将报表保存到数据库中。
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $excel = DB::transaction(function () use ($request) {
            activity()->enableLogging(); // 开启记录操作日志

            $filename = "数据管理-停车记录表";

            $file_path = get_excel_file_path($filename);

            // 以下为即时导出，队列导出写法不同
            (new DmangerCarStop($request))->store($file_path);

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

    /**
     * 总数居管理中的产看图片
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showImage(Request $request)
    {
        // 如果改行程记录没有停车记录就返回一个提示信息
        $car_stop_id = $request->input('id');
        if(!$car_stop_id){
            return $this->responseSuccess('该行程中无停车记录！');
        }

        $query = CarStop::query();

        $query->with(['park','userOrder','userOrder.carApts.aptOrder','userCar']);

        $carStop = $query->where('id',$car_stop_id)->paginate();

        return CarStopResource::collection($carStop);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }
}
