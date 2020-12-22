<?php

namespace App\Http\Controllers\Admin;

use App\Exports\Admin\FinancialManageDetailAccount;
use App\Http\Resources\Admin\DetailAccountResource;
use App\Models\Users\UserBalance;
use App\Models\Users\UserPaymentLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DetailAccountController extends BaseController
{
    /**
     * 记账明细
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $query = UserBalance::query();

        $query->with('order','user');

        $per_page = $request->input('per_page');

        $data = $query->search($request)->paginate($per_page);
        return DetailAccountResource::collection($data);
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
            $filename = "记账明细-数据表";

            $file_path = get_excel_file_path($filename);

            // 以下为即时导出，队列导出写法不同
            (new FinancialManageDetailAccount($request))->store($file_path);

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
