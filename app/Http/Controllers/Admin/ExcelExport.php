<?php


namespace App\Http\Controllers\Admin;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

trait ExcelExport {
    /**
     * @var \App\Exports\ExcelExport
     */
    protected $export;

    /**
     * @var string
     */
    protected $fileName = '报表导出';

    /**
     * 设置导出参数
     * @param Request $request
     */
    protected function setExportParameters(Request $request) {
        //
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws \Throwable
     */
    public function export(Request $request) {
        $this->setExportParameters($request);
        $excel = DB::transaction(function () use ($request) {
            activity()->enableLogging();    //操作日志
            $path = get_excel_file_path($this->fileName);
            $this->export->store($path);
            $size = \Storage::disk('excel')->size($path);
            $size = ceil($size / 1024);
            return \App\Models\ExcelExport::query()->create([
                'excel_name' => $this->fileName,
                'excel_size' => $size,
                'excel_src' => $path,
                'create_excel_time' => now()
            ]);
        });
        if (empty($excel)) {
            return $this->responseFailed('导出失败！');
        }
        return $this->responseSuccess();
    }
}
