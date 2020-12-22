<?php

namespace App\Http\Controllers\Admin;

use App\Exports\DmangerParkIncome;
use App\Http\Resources\Admin\ParkIncomeResource;
use App\Models\Dmanger\CarStopOrder;
use App\Models\Users\UserOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
/**
 * 车场收入的控制器
 * @package App\Http\Controllers\Admin
 */
class ParkIncomeController extends BaseController
{
    /**
     * 总数据管理
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {

        // 满足查询条件进行查询
        $query = UserOrder::query();

        $query->with(['parks','carStop','carApts','car']);

        //订单管理-预约停车传递至此的id
        if($id=$request->input('id')){
            $query->where('id',$id);
        }

        //财务管理-平台收入-用户收益传递至此的date
        if($date=$request->input('date')){
            $query->whereDate('created_at',$date);
        }

        //财务管理-平台收入-车场收益传递至此的park_id
        if($park_id=$request->input('park_id')){
            $query->where('park_id',$park_id);
        }
        //财务管理-平台收入-车场收益传递至此的time
        if($time=$request->input('time')){
            $start_time=Carbon::parse($time)->startOfDay();
            $end_time=Carbon::parse($time)->endOfDay();
            $query->whereBetween('finished_at',[$start_time,$end_time]);
        }

        $per_page = $request->input('per_page');

        $parkIncome = $query->search($request)->orderBy('id','desc')->paginate($per_page);

        return ParkIncomeResource::collection($parkIncome);
    }

    /**
     * 生成报表
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $excel = DB::transaction(function () use ($request) {
            activity()->enableLogging(); // 开启记录操作日志

            $filename = "数据管理-总数据表";

            $file_path = get_excel_file_path($filename);


            // 以下为即时导出，队列导出写法不同
            (new DmangerParkIncome($request))->store($file_path);

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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * 修改
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // 判断是否对支付的停车费进行了调整，如是调整了就增加调整操作的说明
        $user_order = UserOrder::find($id);

        $parking_fee = $request->input('parking_fee');

        if($parking_fee != $user_order->parking_fee){
            $user_order->parking_fee = $parking_fee;

            $user_order->explain = '修改停车支付金额';

            $user_order->save();

            return $this->responseSuccess('修改成功！');
        }
        // 修改停车订单的支付状态
        $car_stop_id = $request->input('car_stop_id');

        $order_status = $request->input('stop_status');
        if($car_stop_id){
            if($order_status){
                $stop_order = CarStopOrder::where('car_stop_id',$car_stop_id)->get();
                if($stop_order){
                    // 判断获取的支付订单状态和之前的状态是否一致
                    if($order_status != $stop_order->status){
                        $stop_order->status = $stop_order;
                        $stop_order->save();

                        $user_order->explain = '修改停车支付状态';
                        $user_order->save();

                        return $this->responseSuccess('修改成功！');
                    }
                }
            }
        }

        return $this->responseSuccess('未修改任何数据！');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
