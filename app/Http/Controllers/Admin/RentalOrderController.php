<?php

namespace App\Http\Controllers\Admin;

use App\Exports\Admin\FinancialManageRentalOrder;
use App\Http\Resources\Admin\ParkIncomeResource;
use App\Http\Resources\Admin\RentalOrderResource;
use App\Models\Users\UserOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RentalOrderController extends BaseController
{
    /**
     * 出租车位订单
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', $this->per_page);

        $query=UserOrder::query();

        $query->whereHas('carRent',function ($query){
            $query->where('rent_type_id',2);
        });

        $query->with('carStop','carApts','carApts.carRent');

        $data=$query->search($request)->orderBy('id','desc')->paginate($perPage);

        return RentalOrderResource::collection($data);
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
            $filename = "出租车位订单-数据表";
            activity()->enableLogging(); // 开启记录操作日志
            $file_path = get_excel_file_path($filename);

            // 以下为即时导出，队列导出写法不同
            (new FinancialManageRentalOrder($request))->store($file_path);

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
