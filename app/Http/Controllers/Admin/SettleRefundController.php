<?php

namespace App\Http\Controllers\Admin;

use App\Exports\Admin\FinancialManageSettleRefund;
use App\Http\Resources\Admin\SettleRefundResource;
use App\Models\Financial\Apply;
use App\Models\Financial\ApplyMiddle;
use App\Models\Users\UserRefund;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SettleRefundController extends BaseController
{
    /**
     * 退款管理列表
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', $this->per_page);
        $query=UserRefund::query();
        $data=$query->search($request)->with('order')->orderBy('id','desc')->paginate($perPage);
        return SettleRefundResource::collection($data);
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
            $filename = "退款订单-数据表";
            activity()->enableLogging(); // 开启记录操作日志
            $file_path = get_excel_file_path($filename);

            // 以下为即时导出，队列导出写法不同
            (new FinancialManageSettleRefund($request))->store($file_path);

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
     * 确认退款
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function store(Request $request)
    {
        $id=$request->input('id');
        $refund=UserRefund::find($id);
        if(!empty($refund['refunded_at'])){
            return $this->responseFailed('该订单已退款',2);
        }
        $result=DB::transaction(function () use ($id,$refund){
//            $refund->refunded_at=now();
//            $refund->save();
            $apply_id=Apply::insertGetId([
                'no'=>get_order_no(),
                'amount'=>$refund['refunded_amount'],
                'payment_number'=>1,
                'business_type'=>2,
                'person_type'=>2,
                'apply_time'=>now(),
            ]);
            $result=ApplyMiddle::create([
                'order_type'=>\App\Models\Users\UserRefund::class,
                'order_id'=>$refund['id'],
                'apply_id'=>$apply_id,
            ]);
            return $result;
        });

        if($result!==false){
            return $this->responseData('','操作成功！','0');
        }else{
            return $this->responseData('','操作失败！','1');
        }
    }
}
