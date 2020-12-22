<?php

namespace App\Http\Controllers\Admin;

use App\Exports\Admin\FinancialAccountManage;
use App\Http\Requests\Admin\ParkAccountRequest;
use App\Http\Resources\Admin\AccountManageResource;
use App\Models\Financial\AccountManage;
use App\Models\Parks\Park;
use App\Models\Regions\City;
use App\Models\Regions\Province;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AccountManageController extends BaseController
{
    /**
     * 车场账号管理
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $query = AccountManage::query();

        $query->with(['park','property']);

        $per_page = $request->input('per_page');

        $data = $query->search($request)->orderBy('id','desc')->paginate($per_page);

        return AccountManageResource::collection($data);
    }

    /**
     * 导出
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function create(Request $request){
        $excel = DB::transaction(function () use ($request) {
            activity()->enableLogging(); // 开启记录操作日志

            $filename = "车场账号-数据表";

            $file_path = get_excel_file_path($filename);

            // 以下为即时导出，队列导出写法不同
            (new FinancialAccountManage($request))->store($file_path);

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

    /**
     * 添加车场的账号
     * @param ParkAccountRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function store(ParkAccountRequest $request)
    {
        //获取验证的数据
        $validated = $request->validated();
        $park_name=$validated['park_name'];
        $park = Park::where('project_name','like',"%$park_name%")->first();
        $province_name = Province::where('province_id',$validated['province_id'])->pluck('name');
        $city_name = City::where('city_id',$validated['city_id'])->pluck('name');
        $result=DB::transaction(function () use ($validated,$park_name,$park,$province_name,$city_name){
            $res=AccountManage::create([
                'park_id'=>$park->id,
                'property_id'=>$park->property_id,
                'contract_id'=>$validated['contract_num'],// 后面有合同表之后要进行查询合同的id，在添加进去
                'account_type'=>$validated['account_type'],
                'bank_name'=>$validated['bank_name'],
                'bank_code'=>$validated['bank_code'],
                'account_province'=>$province_name[0],
                'account_city'=>$city_name[0],
                'account'=>$validated['account'],
                'sub_branch'=>$validated['sub_branch'],
                'account_name'=>$validated['account_name'],
            ]);
            return $res;
        });

        if($result!=false){
            return $this->responseSuccess();
        }
        return $this->responseFailed();
    }

    /**
     * 审核
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id){
        $result=AccountManage::find($id)->update(['audit_status'=>2]);
        if($result!=false){
            return $this->responseSuccess();
        }
    }

    /**
     * 修改车场账号信息
     * @param ParkAccountRequest $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function update(ParkAccountRequest $request, $id)
    {
        //获取验证的数据
        $validated = $request->validated();
        $park_name=$validated['park_name'];
        $park = Park::where('project_name','like',"%$park_name%")->first();
        $province_name = Province::where('province_id',$validated['province_id'])->pluck('name');
        $city_name = City::where('city_id',$validated['city_id'])->pluck('name');
        $result=DB::transaction(function () use ($validated,$park_name,$park,$province_name,$city_name,$id){
            $res=AccountManage::find($id)->update([
                'park_id'=>$park->id,
                'property_id'=>$park->property_id,
                'contract_id'=>$validated['contract_num'],// 后面有合同表之后要进行查询合同的id，在添加进去
                'account_type'=>$validated['account_type'],
                'bank_name'=>$validated['bank_name'],
                'bank_code'=>$validated['bank_code'],
                'account_province'=>$province_name[0],
                'account_city'=>$city_name[0],
                'account'=>$validated['account'],
                'sub_branch'=>$validated['sub_branch'],
                'account_name'=>$validated['account_name'],
            ]);
            return $res;
        });

        if($result!=false){
            return $this->responseSuccess();
        }
        return $this->responseFailed();
    }
}
