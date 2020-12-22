<?php

namespace App\Http\Controllers\Admin;

use App\Exports\Admin\FinancialManagePlatformRecord;
use App\Http\Resources\Admin\PlatformRecordResource;
use App\Models\Bills\OrderAmountDivide;
use App\Models\Bills\ParkBillSummary;
use App\Models\Bills\PlatformBillSummary;
use App\Models\Financial\PlatformFinancialRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PlatformRecordController extends BaseController
{
    /**
     * 平台账单
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', $this->per_page);

        $query = PlatformBillSummary::query();

        $query->search($request);

        $query->latest();

        $data = $query->paginate($perPage);

        return PlatformRecordResource::collection($data)->additional([
            'bill_types' => ParkBillSummary::$billTypeMaps
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
            activity()->enableLogging(); // 开启记录操作日志

            $filename = "平台收支-数据表";

            $file_path = get_excel_file_path($filename);

            // 以下为即时导出，队列导出写法不同
            (new FinancialManagePlatformRecord($request))->store($file_path);

            $excel_size = \Storage::disk('excel')->size($file_path);

            // 将字节转化成kb
            $excel_size = ceil($excel_size / 1024);

            $model = new \App\Models\ExcelExport([
                'excel_name'        => $filename,
                'excel_size'        => $excel_size,
                'excel_src'         => $file_path,
                'create_excel_time' => now()
            ]);

            $model->save();
            return $model;
        });
        if ($excel) {
            return $this->responseSuccess();
        } else {
            return $this->responseFailed('报表生成失败！', '4007');
        }
    }

    /**
     * 车场和业主中的平台收益
     * @param Request $request
     */
    public function earnings(Request $request)
    {

        $fee = array();

        $query = OrderAmountDivide::query();

        $fee['park_fee'] = $query->sum('platform_fee');

        $fee['own_fee'] = $query->where('owner_fee','!=',0)->sum('platform_fee');

        return $this->responseData($fee);
    }
}
