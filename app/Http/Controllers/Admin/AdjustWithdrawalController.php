<?php

namespace App\Http\Controllers\Admin;

use App\Exports\Admin\FinancialManageAdjust;
use App\Http\Resources\Admin\AdjustWithdrawalResource;
use App\Models\Financial\Record;
use App\Models\Financial\Withdrawal;
use App\Models\Users\UserDevice;
use App\Packages\JPush\JPushMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdjustWithdrawalController extends BaseController
{
    /**
     * 提现调整
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', $this->per_page);
        $query=Withdrawal::query();
        $data=$query->search($request)->with('park', 'user')->orderBy('id','desc')->paginate($perPage);
        return AdjustWithdrawalResource::collection($data);
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
            $filename = "提现订单-数据表";
            activity()->enableLogging(); // 开启记录操作日志
            $file_path = get_excel_file_path($filename);

            // 以下为即时导出，队列导出写法不同
            (new FinancialManageAdjust($request))->store($file_path);

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
     * 提交调整信息
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $id=$request->input('id');
        $adjust_type=$request->input('adjust_type');
        $adjust_amount=($request->input('adjust_amount')) * 100;    // 将获取的元转换位分
        $reason=$request->input('reason');
        $is_loss=$request->input('is_loss');
        $user=$request->user();
        $ret = Withdrawal::where('id',$id)->update(['apply_money'=>$adjust_amount]);
        $result=Record::create([
            'record_no'=>get_order_no(),
            'withdrawal_id'=>$id,
            'adjust_amount'=>$adjust_amount,
            'adjust_type'=>$adjust_type,
            'reason'=>$reason,
            'is_loss'=>$is_loss,
            'operator'=>$user['name'],
        ]);
        if($result!=false && $ret != false){
            return $this->responseSuccess('调整成功',0);
        }else{
            return $this->responseFailed('调整失败',1);
        }
    }

    /**
     * 点击结算调整展示的信息
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $withdrawal=Withdrawal::where('id',$id)->with('park','user')->first();
        $record=Record::where('withdrawal_id',$id)->orderBy('id','desc')->first();
        $amount='';//最新结算金额
        if(!empty($record)){
            $amount=$record['adjust_amount'];
        }else{
            $amount=$withdrawal['apply_money'];
        }
        $park_name=$withdrawal['park']['project_name'];
        $nickname = $withdrawal['user']['nickname'];
        $data=[
            'id' => $id,
            'nickname' => $nickname,
            'withdrawal_no'=>$withdrawal['withdrawal_no'],
            'apply_money'=>$withdrawal['apply_money'],
            'amount'=>$amount,
            'park_name'=>$park_name?$park_name:' ',
        ];
        return $this->responseData($data);
    }

    /**
     * 修改审核状态
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $status=$request->input('adjust_status');
        $user=$request->user();
        if($status==1){
            $result=Withdrawal::where('id',$id)->update(['admin_id'=>$user['id'],'audit_time'=>date('Y-m-d H:i:s',time())]);
            if($result!==false){

                // 推送极光消息
                $user_id = (Withdrawal::find($id))->user_id;    // 获取提现用户的id，推送消息给指定用户

                // 针对用户单个手机发送，只需传送jpush_id
                // $user_device = UserDevice::query()->where('user_id',$user_id)->first();
                // $jpush_id = $user_device->jpush_id;

                $j_msg = new JPushMessage();

                $data = $j_msg->platform( 'all')
                    ->audience('alias',[$user_id])      // 推送给用户所有登录的手机
                    // ->cid($message->no)
                    ->alert('提现处理成功')
                    ->toArray();

                app('jpush.push')->send($data);

                return $this->responseSuccess('审核通过并已发送消息通知用户');
            }else{
                return $this->responseFailed('修改失败','1');
            }
        }elseif ($status==2){
            $result=Withdrawal::where('id',$id)->update(['admin_id'=>null,'audit_time'=>null]);
            if($result!==false){
                return $this->responseSuccess('修改成功');
            }else{
                return $this->responseFailed('修改失败','1');
            }
        }
    }
}
