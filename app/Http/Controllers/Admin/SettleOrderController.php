<?php

namespace App\Http\Controllers\Admin;

use App\Exports\Admin\FinancialManageSettleOrder;
use App\Http\Resources\Admin\ParkIncomeResource;
use App\Http\Resources\Admin\SettleOrderDetailResource;
use App\Http\Resources\Admin\SettleOrderResource;
use App\Models\Dmanger\CarAptOrder;
use App\Models\Users\UserOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class SettleOrderController extends BaseController
{
    /**
     * 结算管理-订单管理
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {

        // 满足查询条件进行查询
        $query = UserOrder::query();

        $query->with(['parks','carStop','carApts','car']);

        $per_page = $request->input('per_page');

        $parkIncome = $query->search($request)->orderBy('id','desc')->paginate($per_page);

        return SettleOrderResource::collection($parkIncome);
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
            $filename = "结算管理-订单管理表";
            activity()->enableLogging(); // 开启记录操作日志
            $file_path = get_excel_file_path($filename);

            // 以下为即时导出，队列导出写法不同
            (new FinancialManageSettleOrder($request))->store($file_path);

            $excel_size = \Storage::disk('excel')->size($file_path);
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
            return $this->responseData('','报表生成成功！','0');
        }else{
            return $this->responseData('','报表生成失败！','1');
        }
    }

    /**
     * 申请退款提交
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function store(Request $request)
    {
        $refund=DB::transaction(function () use ($request){
            $order_id=$request->input('order_id');
            $refund_type=$request->input('refund_type');
            $refund_way=$request->input('refund_way');
            $refund_channels=$request->input('refund_channels')??null;
            $refund_account=$request->input('transfer_account')??null;
            $refunded_amount=$request->input('refunded_amount')??null;
            $reason=$request->input('reason')??null;
            $order=UserOrder::find($order_id);
            $result=UserOrder::where('id',$order_id)->update(['status'=>'refunded','refund_amount'=>$refunded_amount]);
            if($result){
                $model=new \App\Models\Users\UserRefund([
                    'order_type'=>UserOrder::class,
                    'order_id'=>$order['id'],
                    'user_id'=>$order['user_id'],
                    'amount'=>$order['total_amount'],//订单金额
                    'refunded_amount'=>$refunded_amount,//退款金额
                    'transfer_account'=>$refund_account,
                    'type'=>$refund_type,
                    'refund_no'=>get_order_no(),
                    'reason'=>$reason,
                    'refund_way'=>$refund_way,
                    'refund_channels'=>$refund_channels
                ]);
                $model->save();
                return $model;
            }
        });
        if($refund){
            return $this->responseData('','申请成功！','0');
        }else{
            return $this->responseData('','申请失败，请稍后再试!','1');
        }
    }

    /**
     * 退款列表
     * @param $id
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function show($id)
    {
        $data=UserOrder::where('id',$id)->paginate();
        return SettleOrderDetailResource::collection($data);
    }
}
