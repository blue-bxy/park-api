<?php

namespace App\Http\Controllers\Admin;

use App\Http\Resources\Admin\ReminderRecordResource;
use App\Models\ExcelExport;
use App\Models\Financial\Reminder;
use App\Models\Financial\ReminderRecord;
use App\Models\Users\UserDevice;
use App\Packages\JPush\JPushMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReminderRecordController extends BaseController
{
    public function index(Request $request)
    {
        $per_page = $request->input('per_page');

        $query = ReminderRecord::query();

        $query->with(['reminder','reminder.park','admin']);

        $reminder_records = $query->search($request)->latest()->paginate($per_page);

        return ReminderRecordResource::collection($reminder_records);
    }

    public function export(Request $request)
    {
        // 当传入status有效的时候就是导出催收记录的报表，否则就是催收管理列表的报表
        DB::transaction(function () use ($request) {
            activity()->enableLogging(); // 开启记录操作日志

            $filename = "后付费管理-催收管理记录表";

            $file_path = get_excel_file_path($filename);

            // 以下为即时导出，队列导出写法不同
            (new \App\Exports\Admin\ReminderRecordExport($request))->store($file_path);

            $excel_size = \Storage::disk('excel')->size($file_path);

            // 将字节转化成kb
            $excel_size = ceil($excel_size/1024);

            $model = new ExcelExport([
                'excel_name'        => $filename,
                'excel_size'        => $excel_size,
                'excel_src'         => $file_path,
                'create_excel_time' => now()
            ]);

            $model->save();

            return $model;
        });

        return $this->responseSuccess();
    }
}
