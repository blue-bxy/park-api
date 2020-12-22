<?php

namespace App\Http\Controllers\Admin;

use App\Http\Resources\Admin\ReminderResource;
use App\Models\ExcelExport;
use App\Models\Financial\Reminder;
use App\Models\Financial\ReminderRecord;
use App\Packages\JPush\JPushMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReminderController extends BaseController
{

    /**
     * 待收金额统计
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function reminderAmount()
    {
        $reminder = new Reminder();

        $amount = $reminder->amount();

        return $this->responseData($amount);
    }

    /**
     * 催收管理列表
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $per_page = $request->input('per_page');

        $query = Reminder::query();

        $query->where('pay_status','pending');

        $query->with(['user','park']);

        $query->search($request);

        $reminder = $query->orderBy('id','desc')->paginate($per_page);

        return ReminderResource::collection($reminder);
    }

    /**
     *催收管理报表导出
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function export(Request $request)
    {
        // 当传入status有效的时候就是导出催收记录的报表，否则就是催收管理列表的报表
        DB::transaction(function () use ($request) {
            activity()->enableLogging(); // 开启记录操作日志

            $filename = "后付费管理-催收管理表";

            $file_path = get_excel_file_path($filename);

            // 以下为即时导出，队列导出写法不同
            (new \App\Exports\Admin\Reminder($request))->store($file_path);

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

    /**
     * 添加催收记录
     *
     * @param Request $request
     * @param $state
     * @throws \Throwable
     */
    public function addRecords(Request $request,$state)
    {
        DB::transaction(function () use($request,$state){
            $id = $request->input('id');
            // 修改催收管理的催收状态
            // 催收状态等级依次递增，推送通知(app推送) < 短信 < 人工催熟(电话)，保留等级高的状态
            $reminder = Reminder::find($id);

            $old_state = $reminder->state;

            if($state > $old_state){
                $reminder->state = $state;
                $reminder->save();
            }

            // 添加催收记录
            $reminder_record = new ReminderRecord();
            $reminder_record->reminder_id = $id;
            $reminder_record->admin_id = ($request->user())->id;
            $reminder_record->state = $state;
            $reminder_record->feedback = $request->input('feedback');
            $reminder_record->save();

            // 发送对应的通知
            switch($state){
                // state等于2就是app推送通知
                case "2":
                    $reminder = Reminder::find($id);
                    $amount = $reminder->deduct_amount; // 实付停车费
                    $days_overdue = $reminder->days_overdue;    // 逾期天数
                    // 推送极光消息
                    $user_id = $reminder->user_id;    // 获取提现用户的id，推送消息给指定用户
                    // 针对用户单个手机发送，只需传送jpush_id
                    // $user_device = UserDevice::query()->where('user_id',$user_id)->first();
                    // $jpush_id = $user_device->jpush_id;
                    $j_msg = new JPushMessage();
                    $data = $j_msg->platform( 'all')
                        ->audience('alias',[$user_id])      // 推送给用户所有登录的手机
                        // ->cid($message->no)
                        ->alert("您好，您有一笔金额为{$amount}元的停车费订单已逾期{$days_overdue}天，请点击此处立即处理。")
                        ->toArray();
                    app('jpush.push')->send($data);
                    break;
                // state等于3就是短信
                case "3":

                    break;
                // state等于4就是人工催收，客服拨打用户电话
            }
        });

        return $this->responseSuccess('操作成功！');
    }

    /**
     * 自动催收设置列表
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function getReminderSet()
    {
        $data = array();

        $data['reminder'] = settings()->get('reminder');

        $data['reminder_time'] = settings()->get('reminder_time');

        return $this->responseData($data);
    }

    /**
     * 设置自动催收设置
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function reminderSet(Request $request)
    {
        $reminder = new Reminder();
        $ret = $reminder->reminderSet($request);
        if($ret){
            return $this->responseSuccess('保存设置成功！');
        }
    }
}
