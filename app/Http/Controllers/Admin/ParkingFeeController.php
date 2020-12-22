<?php

namespace App\Http\Controllers\Admin;

use App\Exports\Admin\FinancialManageParkingFee;
use App\Http\Resources\Admin\ParkingFeeResource;
use App\Models\Financial\ParkingFee;
use App\Models\Parks\Park;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ParkingFeeController extends BaseController
{
    /**
     * 停车手续费
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', $this->per_page);
        $query=ParkingFee::query();
        $data=$query->search($request)->with('park','admin')->paginate($perPage);
        return ParkingFeeResource::collection($data);
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
            $filename = "停车手续费-数据表";
            activity()->enableLogging(); // 开启记录操作日志
            $file_path = get_excel_file_path($filename);
            (new FinancialManageParkingFee($request))->store($file_path);
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

    /**
     * 新建费率
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function store(Request $request)
    {
        $park_name=$request->input('park_name');
        $park = Park::where('project_name','like',"%$park_name%")->first();
        $user=$request->user();
        if(empty($park)){
            return $this->responseFailed('该停车场不存在',2);
        }
        $exists=ParkingFee::where('park_id',$park->id)->exists();
        if($exists){
            return $this->responseFailed('该停车场已设置过费率标准',1);
        }
        $fee=$request->input('fee');
        $result=DB::transaction(function ()use($park,$fee,$user){
            $re=ParkingFee::create([
               'park_id'=>$park->id,
               'fee'=>$fee,
                'user_id'=>$user->id
            ]);
            return $re;
        });
        if($result!=false){
            return $this->responseSuccess('新增成功');
        }
    }


    /**
     * 修改费率
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function update(Request $request, $id)
    {
        $park_name=$request->input('park_name');
        $park = Park::where('project_name','like',"%$park_name%")->first();
        $user=$request->user();
        if(empty($park)){
            return $this->responseFailed('该停车场不存在',1);
        }
        $fee=$request->input('fee');
        $result=DB::transaction(function ()use($id,$park,$fee,$user){
            $re=ParkingFee::find($id)->update([
                'park_id'=>$park->id,
                'fee'=>$fee,
                'user_id'=>$user->id
            ]);
            return $re;
        });
        if($result!=false){
            return $this->responseSuccess('修改成功');
        }
    }
}
