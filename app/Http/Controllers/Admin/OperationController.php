<?php

namespace App\Http\Controllers\Admin;

use App\Exports\Admin\FinancialManageOperation;
use App\Http\Resources\Admin\OperationResource;
use App\Models\Financial\AccountManage;
use App\Models\Financial\Apply;
use App\Models\Financial\ApplyMiddle;
use App\Models\Financial\Record;
use App\Models\Financial\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OperationController extends BaseController
{
    /**
     * 提现操作
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', $this->per_page);
        $query=Withdrawal::query();
        $data=$query->search($request)->with('park','user','reviewer')->where('status','!=','3')->where('admin_id','!=','')->orderBy('audit_time','desc')->paginate($perPage);
        return OperationResource::collection($data);
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
            $filename = "提现操作-数据表";
            activity()->enableLogging(); // 开启记录操作日志
            $file_path = get_excel_file_path($filename);

            // 以下为即时导出，队列导出写法不同
            (new FinancialManageOperation($request))->store($file_path);

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
     * 冻结
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $arr_id=explode(',',$id);
        $result='';
        foreach ($arr_id as $id){
            $data=Withdrawal::find($id);
            $account=AccountManage::where('park_id',$data['park_id'])->first();
            if(empty($account['banned_withdraw']) && !empty($account)){
                $account->banned_withdraw=now();
                $result=$account->save();
            }
        }
        if($result!=false){
            return $this->responseSuccess('冻结成功');
        }
    }

    /**
     * 解冻
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id)
    {
        $arr_id=explode(',',$id);
        $result='';
        foreach ($arr_id as $id){
            $data=Withdrawal::find($id);
            $account=AccountManage::where('park_id',$data['park_id'])->first();
            if(!empty($account['banned_withdraw'])){
                $account->banned_withdraw=null;
                $result=$account->save();
            }
        }
        if($result!=false){
            return $this->responseSuccess('解冻成功');
        }
    }

    /**
     * 申请付款
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function edit($id)
    {
        $arr_id=explode(',',$id);
        //判断是否存在汇款中订单
        $exists='';
        foreach ($arr_id as $id){
            $exists=Withdrawal::where('status','2')->where('id',$id)->exists();
        }
        if($exists){
            return $this->responseFailed('存在汇款中订单',1);
        }else{
            $result=DB::transaction(function () use ($arr_id) {
            $amount=0;
            $person_type='';
            foreach ($arr_id as $id){
                $withdrawal=Withdrawal::find($id);
                $person_type=$withdrawal['person_type'];
                if($withdrawal['status']!=2){
                    $withdrawal->status=2;
                    $withdrawal->save();
                }
                $record=Record::where('withdrawal_id',$id)->orderBy('id','desc')->first('adjust_amount');
                //如果该提现单被调整过，则得出调整金额
                if($record!=''){
                    $amount+=intval($record['adjust_amount']*100);
                }else{
                    $amount+=intval($withdrawal['apply_money']*100);
                }
            }
            $apply_id=Apply::insertGetId([
                'no'=>get_order_no(),
                'amount'=>$amount,
                'payment_number'=>count($arr_id),
                'business_type'=>1,
                'person_type'=>$person_type,
                'apply_time'=>now(),
            ]);
            $result='';
            foreach ($arr_id as $id){
                $result=ApplyMiddle::create([
                    'order_type'=>\App\Models\Financial\Withdrawal::class,
                    'order_id'=>$id,
                    'apply_id'=>$apply_id,
                ]);
            }
            return $result;
        });
        if($result){
                return $this->responseSuccess('申请成功');
         }
        }
    }
}
