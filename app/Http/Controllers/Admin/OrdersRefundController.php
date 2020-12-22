<?php

namespace App\Http\Controllers\Admin;

use App\Exports\Admin\FinancialManageOrderRefund;
use App\Http\Resources\Admin\OrdersRefundResource;
use App\Models\Users\UserRefund;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrdersRefundController extends BaseController
{
    /**
     * 订单管理-退款列表
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', $this->per_page);
        $query=UserRefund::query();
        $data=$query->search($request)->with('order')->paginate($perPage);
        return OrdersRefundResource::collection($data);
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
            $filename = "订单退款-数据表";
            activity()->enableLogging(); // 开启记录操作日志
            $file_path = get_excel_file_path($filename);

            // 以下为即时导出，队列导出写法不同
            (new FinancialManageOrderRefund($request))->store($file_path);

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
}
