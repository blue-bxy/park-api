<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\ExcelResource;
use App\Models\Dmanger\ExportExcel;
use App\Models\ExcelExport;
use Illuminate\Http\Request;
/**
 * 报表导出控制器
 * @package App\Http\Controllers\Admin
 */
class ExcelController extends BaseController
{
    /**
     * 显示报表导出的页面
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $query = ExportExcel::query();

        $per_page = $request->input('per_page');

        $query->orderBy('created_at','desc');
        $data = $query->search($request)->paginate($per_page);

        return ExcelResource::collection($data);
    }


    public function download(Request $request, $id)
    {
        $data = ExportExcel::find($id);

        $file_path = $data->excel_src;

        return \Storage::disk('excel')->download($file_path,  $data->excel_name);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(ExportExcel $exportExcel,$id)
    {
        $data = ExportExcel::find($id);

        $data->forceDelete();

        $file_path = $data->excel_src;

        return \Storage::disk('excel')->delete($file_path);
    }
}
