<?php

namespace App\Http\Controllers\Admin;

use App\Exports\Admin\FinancialManageBadCredit;
use App\Http\Resources\Admin\BadCreditResource;
use App\Models\Financial\BadCredit;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BadCreditController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', $this->per_page);
        $query=Payment::query();
        $data=$query->search($request)->with('user')->where('status','failed')->paginate($perPage);
        return BadCreditResource::collection($data);
    }

    /**
     * Show the form for creating a new resource.
     *导出
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $excel = DB::transaction(function () use ($request) {
            $filename = "坏账收款-数据表";

            $file_path = get_excel_file_path($filename);

            // 以下为即时导出，队列导出写法不同
            (new FinancialManageBadCredit($request))->store($file_path);

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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $result=Payment::destroy($id);
        if($result!=false){
            return $this->responseSuccess('清除成功');
        }
    }
}
