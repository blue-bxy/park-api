<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Http\Resources\ActivityLogResource;
use App\Models\ActivityLog;
use App\Models\Admin;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ActivityLogController extends BaseController
{
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', $this->per_page);

        $query = ActivityLog::query();


        // 给个默认的查询时间为近31天
        $start_time = date('Y-m-d H:i:s',strtotime("-31 day"));

        if ($time = $request->input('time')) {
            $query->where('created_at','>=',$time);
        }else{
            $query->where('created_at','>=',$start_time);
        }

        if ($end_time = $request->input('end_time')) {
            $query->where('created_at','<=',$end_time);
        }

        if ($user_name = $request->input('user_name')) {
            $query->whereHasMorph('causer', [Admin::class, Property::class], function ($query) use ($user_name) {
                $query->where("name", 'like', "%{$user_name}%");
            });
        }

        // 时间区间范围00000.

        $query->with('causer');

        $logs = $query->latest()->paginate($perPage);

        return ActivityLogResource::collection($logs);
    }

    public function create(Request $request)
    {
        $excel = DB::transaction(function () use ($request) {
            activity()->enableLogging(); // 开启记录操作日志

            $filename = "数据管理-用户操作记录表";

            $file_path = get_excel_file_path($filename);

            // 以下为即时导出，队列导出写法不同
            (new \App\Exports\ActivityLog($request))->store($file_path);

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
}
