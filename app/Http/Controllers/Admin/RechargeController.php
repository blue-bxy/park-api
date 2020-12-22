<?php

namespace App\Http\Controllers\Admin;

use App\Exports\Admin\FinancialManageRecharge;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\RechargeResource;
use App\Http\Resources\Admin\UserPaymentLogResource;
use App\Models\Recharge;
use App\Models\Users\UserPaymentLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RechargeController extends BaseController
{
    /**
     * 充值订单
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', $this->per_page);
        $query=Recharge::query();
        $query->where('status','paid');
        $data=$query->search($request)->with('user')->orderBy('id','desc')->paginate($perPage);
        return RechargeResource::collection($data);
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
            $filename = "充值订单-数据表";
            activity()->enableLogging(); // 开启记录操作日志
            $file_path = get_excel_file_path($filename);
                //车主提现生成报表
            (new FinancialManageRecharge($request))->store($file_path);

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
