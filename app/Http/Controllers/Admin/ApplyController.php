<?php

namespace App\Http\Controllers\Admin;

use App\Exports\Admin\FinancialManageDetailApply;
use App\Http\Resources\Admin\ApplyDetailResource;
use App\Http\Resources\Admin\ApplyResource;
use App\Models\Financial\AccountManage;
use App\Models\Financial\Apply;
use App\Models\Financial\ApplyMiddle;
use App\Models\Financial\Record;
use App\Models\Financial\Withdrawal;
use App\Models\Users\UserAuthAccount;
use App\Models\Users\UserRefund;
use App\Packages\Payments\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApplyController extends BaseController
{
    /**
     * 付款申请
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', $this->per_page);
        $query=Apply::query();
        $data=$query->search($request)->orderBy('apply_time','desc')->paginate($perPage);
        return ApplyResource::collection($data);
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
            $filename = "付款申请-数据表";
            activity()->enableLogging(); // 开启记录操作日志
            $file_path = get_excel_file_path($filename);

            // 以下为即时导出，队列导出写法不同
            (new FinancialManageDetailApply($request))->store($file_path);

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
     * 付款展示数据
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function show(Request $request,$id)
    {
        $perPage = $request->input('per_page', $this->per_page);
        $data=ApplyMiddle::where('apply_id',$id)->with('order')->paginate($perPage);
        return ApplyDetailResource::collection($data);
    }

    /**
     * 付款
     * @param Request $request
     * @throws \Throwable
     */
    public function payment(Request $request){

        $res = DB::transaction(function () use ($request) {

            $id=$request->input('id');

            $data=ApplyMiddle::where('apply_id',$id)->with('order')->get();

            // 线下付款，点击付款时直接修改退款的记录或者提现记录的状态即可
            foreach ($data as $v){

                if($v['order_type']==UserRefund::class){

                    $user_refund = UserRefund::find($v['order_id']);

                    $user_refund->refunded_at=now();

                    $user_refund->save();
                }

                if($v['order_type']==Withdrawal::class){

                    $withdrawal_id = $v['order']['id'];

                    Withdrawal::where('id',$withdrawal_id)->update(['status'=>3,'completion_time'=>now()]);
                }

                // 完善付款申请状态
                $apply = Apply::find($id);

                $apply->success_number = $apply->payment_number;

                $apply->submit = 2;

                $apply->status = 3;

                $apply->payment_time = now();

                $apply->complete_time = now();

                $apply->agent = ($request->user())->name;

                $apply->save();
            }

            return true;
        });

        if(!$res){
            return $this->responseFailed();
        }

        return $this->responseSuccess('订单完成！');

//        $id=$request->input('id');
//        $data=ApplyMiddle::where('apply_id',$id)->with('order')->get();
//        $options=array();
//        $payment=new Payment();
//        $result=DB::transaction(function () use ($data,$options,$payment){
//            foreach ($data as $value){
//                $user=UserAuthAccount::where('user_id',$value['order']['user_id'])->get(['openid','nickname']);
//                if($value['order_type']==UserRefund::class){
//                    $options['openid']=$user[0]['openid'];
//                    $options['partner_no']=get_order_no();
//                    $options['check_name']='NO_CHECK';
//                    $options['amount']=$value['order']['refunded_amount'];
//                    $options['desc']='付款退款金额';
//                    $result=$payment->transfer($options);
//                }elseif ($value['order_type']==Withdrawal::class){
//                    $adjust_amount=Record::where('withdrawal_id',$value['order']['id'])->pluck('adjust_amount')[0];
//                    if($value['order']['person_type']==1){
//                        $account=AccountManage::where('park_id',$value['order']['park_id'])->get(['account','account_name','bank_code']);
//                        $options['partner_no']=get_order_no();
//                        $options['bank_no']=$account[0]['account'];
//                        $options['true_name']=$account[0]['account_name'];
//                        $options['bank_code']=$account[0]['bank_code'];
//                        if(!empty($adjust_amount)){//如果该提现单有被调整过则传调整金额
//                            $options['amount']=$adjust_amount;
//                        }else{
//                            $options['amount']=$value['order']['apply_money'];
//                        }
//                        $options['amount']=$value['order']['apply_money'];
//                        $options['desc']='付款物业提现金额';
//                        $result=$payment->transfer($options);
//                    }elseif($value['order']['person_type']==2){
//                        $options['openid']=$user[0]['openid'];
//                        $options['partner_no']=get_order_no();
//                        $options['check_name']='NO_CHECK';
//                        if(!empty($adjust_amount)){
//                            $options['amount']=$adjust_amount;
//                        }else{
//                            $options['amount']=$value['order']['apply_money'];
//                        }
//                        $options['desc']='付款车主提现金额';
//                        $result=$payment->transfer($options);
//                    }
//                }
//            }
//        });
    }

    /**
     * 拒绝付款
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function edit($id)
    {
        $data=ApplyMiddle::where('apply_id',$id)->with('order')->get();
        $result=DB::transaction(function () use ($data,$id){
            Apply::find($id)->update(['submit'=>3]);
            $result='';
            foreach ($data as $value){
                if($value['order_type'] == Withdrawal::class){
                    $result=Withdrawal::find($value['order_id'])->update(['status'=>1,'admin_id'=>null]);
                }else{
                    $result=UserRefund::find($value['order_id'])->update(['refunded_at'=>null]);
                }
            }
            return $result;
        });
        if($result!=false){
            return $this->responseSuccess('拒绝成功');
        }
    }

    /**
     * 明细
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function update(Request $request, $id)
    {
        $perPage = $request->input('per_page', $this->per_page);
        $data=ApplyMiddle::where('apply_id',$id)->with('order')->paginate($perPage);
        return ApplyDetailResource::collection($data);
    }
}
