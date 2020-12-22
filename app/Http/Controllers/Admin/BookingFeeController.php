<?php

namespace App\Http\Controllers\Admin;

use App\Exports\FinancialManageBookingFee;
use App\Http\Requests\Admin\BookingFeeRequest;
use App\Http\Resources\Admin\BookingFeeResource;
use App\Models\Dmanger\CarApt;
use App\Models\Financial\BookingFee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingFeeController extends BaseController
{
    /**
     * 预约手续费
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {

        $query=BookingFee::query();

        if($park_name = $request->input('park_name')){

            $query->whereHas('park',function ($query) use($park_name){

                $query->where('project_name','like',"%$park_name%");
            });
        }

        $perPage = $request->input('per_page', $this->per_page);

        $data = $query->with('park','user')->orderBy('id','desc')->paginate($perPage);

        return BookingFeeResource::collection($data);
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
            $filename = "预约手续费-数据表";
            activity()->enableLogging(); // 开启记录操作日志
            $file_path = get_excel_file_path($filename);
            //物业提现生成报表
            (new FinancialManageBookingFee($request))->store($file_path);
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
     * 新增
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(BookingFeeRequest $request)
    {

        $model = new BookingFee();

        $data = $model->data($request);

        if(!$data){
            return $this->responseFailed('请填写正确的分成比例，对应比例和为100！');
        }

        $res = $model->create($data);

        if(!$res){
            return $this->responseFailed();
        }

        return $this->responseSuccess('新增成功');
    }

    /**
     * 详情
     * @param $id
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function show($id)
    {
        $data=BookingFee::where('id',$id)->paginate();

        return BookingFeeResource::collection($data);
    }

    /**
     * 修改展示信息
     * @param $id
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function edit($id)
    {
        $data=BookingFee::where('id',$id)->paginate();
        return BookingFeeResource::collection($data);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {

    }

    /**
     * 确认修改
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function change(BookingFeeRequest $request,$id)
    {
        $model = new BookingFee();

        $data = $model->data($request);

        $obj = $model->find($id);

        // 修改费率之前必须保证改车场至少有一个费率是启用的，否则提示不能停用
        $park_id = $obj->park_id;

        $count = BookingFee::query()->where('park_id',$park_id)->count();

        if($count == 1){

            if($data['status'] == 1){
                return $this->responseFailed('该费率不能停用，必须保证停车场至少有一个费率是启用的！');
            }
        }

        $res = $model->where('id',$id)->update($data);

        if(!$res){
            return $this->responseFailed();
        }

        return $this->responseSuccess('修改成功');
    }

    /**
     * 删除
     * @param $id
     */
    public function destroy($id)
    {
        $model = BookingFee::find($id);

        // 删除费率之前必须保证改车场至少有一个费率存在，否则提示不能删除
        $park_id = $model->park_id;

        $count = BookingFee::query()->where('park_id',$park_id)->count();

        if($count == 1){

            return $this->responseFailed('该费率不能删除，必须保证停车场至少有一个费率！');
        }

        $res = $model->delete($id);

        if(!$res){
            return $this->responseFailed();
        }

        return $this->responseSuccess('删除成功！');
    }
}
