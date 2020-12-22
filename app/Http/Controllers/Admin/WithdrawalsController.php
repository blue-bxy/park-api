<?php

namespace App\Http\Controllers\Admin;

use App\Exports\Admin\FinancialManageWithdrawal;
use App\Http\Resources\Admin\WithdrawalResource;
use App\Models\Financial\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WithdrawalsController extends BaseController
{
    /**
     * index
     *提现管理
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', $this->per_page);

        $query = Withdrawal::query();

        $query->orderBy('apply_time','desc');
        $data = $query->search($request)
            ->with('park', 'user', 'reviewer')
            ->paginate($perPage);

        return WithdrawalResource::collection($data)->additional([
            'status' => Withdrawal::$statusMap,
            'personTypes' => Withdrawal::$personTypes
        ]);
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
            $filename = "财务管理-提现数据表";
            activity()->enableLogging(); // 开启记录操作日志
            $file_path = get_excel_file_path($filename);
            //物业提现生成报表
            (new FinancialManageWithdrawal($request))->store($file_path);
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
}
