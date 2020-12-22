<?php

namespace App\Models\Financial;

use App\Models\Admin;
use App\Models\Dmanger\CarStop;
use App\Models\EloquentModel;
use App\Models\Parks\Park;
use App\Models\Parks\ParkSpace;
use App\Models\User;
use App\Models\Users\UserCar;
use App\Models\Users\UserOrder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class Reminder extends EloquentModel
{
    use SoftDeletes;

    public static $logName = "reminder";

    protected $fillable = [
        'user_order_id','park_id','park_space_id','user_id','user_car_id','car_num',
        'car_in_time','car_out_time','stop_time','amount','deduct_amount','state',
    ];

    public function userOrder()
    {
        return $this->belongsTo(UserOrder::class,'user_order_id');
    }

    public function park()
    {
        return $this->belongsTo(Park::class,'park_id');
    }

    public function parkSpace()
    {
        return $this->belongsTo(ParkSpace::class,'park_space_id');
    }

    public function stop()
    {
        return $this->belongsTo(CarStop::class,'car_stop_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function userCar()
    {
        return $this->belongsTo(UserCar::class,'user_car_id');
    }

    /**
     * 搜索
     * @param Builder $query
     * @param Request $request
     */
    public function scopeSearch(Builder $query,Request$request)
    {
        if($car_num = $request->input('car_num')) {
            $query->where('car_num', 'like', "%$car_num%");
        }

        if($order_no = $request->input('order_no')) {
            $query->where('order_no', 'like', "%$order_no%");
        }

        if($phone = $request->input('phone')) {
            $query->where('phone', 'like', "%$phone%");
        }

        if($project_name = $request->input('park_name')){
            $query->whereHas('park',function($query) use($project_name){
                $query->where('project_name','like',"%$project_name%");
            });
        }

        if($state = $request->input('state')){
            $query->where('state',$state);
        }

        // 逾期日期
        if($start_days_overdue= $request->input('start_days_overdue')){
            $query->where('days_overdue','>=',$start_days_overdue);
        }

        if($end_days_overdue= $request->input('end_days_overdue')){
            $query->where('days_overdue','<=',$end_days_overdue);
        }

        // 进场时间
        if($start_in_time = $request->input('start_in_time')){
            $query->where('car_in_time','>=',$start_in_time);
        }

        if($end_in_time = $request->input('end_in_time')){
            $query->where('car_in_time','<=',$end_in_time);
        }

        // 出场时间
        if($start_out_time = $request->input('start_out_time')){
            $query->where('car_out_time','>=',$start_out_time);
        }

        if($end_out_time = $request->input('end_out_time')){
            $query->where('car_out_time','<=',$end_out_time);
        }

    }

    /**
     * 待收金额统计
     * @return array
     */
    public function amount()
    {
        $amount = array();
        $amount['all'] = Reminder::where('pay_status','pending')->sum('amount');
        $amount['seven'] = Reminder::where('days_overdue','<=',7)->where('pay_status','pending')->sum('amount');
        $amount['fifteen'] = Reminder::whereBetween('days_overdue',[8,15])->where('pay_status','pending')->sum('amount');
        $amount['thirty'] = Reminder::whereBetween('days_overdue',[16,30])->where('pay_status','pending')->sum('amount');
        $amount['sixty'] = Reminder::whereBetween('days_overdue',[31,60])->where('pay_status','pending')->sum('amount');
        $amount['hundred_twenty'] = Reminder::whereBetween('days_overdue',[61,120])->where('pay_status','pending')->sum('amount');
        $amount['exceed_hundred_twenty'] = Reminder::where('days_overdue','>=',120)->where('pay_status','pending')->sum('amount');
        return $amount;
    }

    /**
     * 自动催收设置
     * @param $request
     * @return bool
     * @throws \Exception
     */
    public function reminderSet($request)
    {
        // 保存样式
//        $reminder = [
//            // days是逾期天使，push_msg推送通知(0-不发送，1-发送)，note短信(0-不发，1-发)，times次数，time_slot发送时间段，auto是否自动推送(0-不自动，1自动)
//            ['days'=> '7','push_msg'=>'1','note'=>'1','times'=>'1','auto'=>'1',
//                'time_slot'=>[['08:00','09:00']]
//            ],
//
//            ['days'=> '60','push_msg'=>'0','note'=>'0','times'=>'0','auto'=>'0','time_slot'=>[]]
//        ];
//
//        $reminder_time  = [
//            '1'=>[['08:00','09:00']],
//            '2'=>[['08:00','09:00'],['12:00','13:00']],
//            '3'=>[['08:00','09:00'],['12:00','13:00'],['17:00','18:00']]
//        ];

        // 获取对应逾期天数的数据
        $first = array();
        $first['days'] = $request->input('first_days');
        $first['push_msg'] = $request->input('first_push_msg');
        $first['note'] = $request->input('first_note');
        $first['times'] = $request->input('first_times');
        if($first['push_msg'] || $first['note'] ){
            $first['auto'] = 1;
        }else{
            $first['auto'] = 0;
        }

        $second = array();
        $second['days'] = $request->input('second_days');
        $second['push_msg'] = $request->input('second_push_msg');
        $second['note'] = $request->input('second_note');
        $second['times'] = $request->input('second_times');
        if($second['push_msg'] || $second['note'] ){
            $second['auto'] = 1;
        }else{
            $second['auto'] = 0;
        }

        $third = array();
        $third['days'] = $request->input('third_days');
        $third['push_msg'] = $request->input('third_push_msg');
        $third['note'] = $request->input('third_note');
        $third['times'] = $request->input('third_times');
        if($third['push_msg'] || $third['note'] ){
            $third['auto'] = 1;
        }else{
            $third['auto'] = 0;
        }

        $fourth = array();
        $fourth['days'] = $request->input('fourth_days');
        $fourth['push_msg'] = $request->input('fourth_push_msg');
        $fourth['note'] = $request->input('fourth_note');
        $fourth['times'] = $request->input('fourth_times');
        if($fourth['push_msg'] || $fourth['note'] ){
            $fourth['auto'] = 1;
        }else{
            $fourth['auto'] = 0;
        }

        // 保存每轮对应的时间
        $time1 = array();
        array_push($time1,[$request->input('time1_start'),$request->input('time1_end')]);

        $time2 = array();
        $time2_start1 = $request->input('time2_start1');
        $time2_end1 = $request->input('time2_end1');
        $time2_start2 = $request->input('time2_start2');
        $time2_end2 = $request->input('time2_end2');
        array_push($time2,[$time2_start1,$time2_end1]);
        array_push($time2,[$time2_start2,$time2_end2]);

        $time3 = array();
        $time3_start1 = $request->input('time3_start1');
        $time3_end1 = $request->input('time3_end1');
        $time3_start2 = $request->input('time3_start2');
        $time3_end2 = $request->input('time3_end2');
        $time3_start3 = $request->input('time3_start3');
        $time3_end3 = $request->input('time3_end3');

        array_push($time3,[$time3_start1,$time3_end1]);
        array_push($time3,[$time3_start2,$time3_end2]);
        array_push($time3,[$time3_start3,$time3_end3]);

        $reminder_time = array();
        $reminder_time[1] = $time1;
        $reminder_time[2] = $time2;
        $reminder_time[3] = $time3;

        // 保存对应逾期天数内的每轮对应的推送时间
        if($first['times']){
            $first['time_slot'] = $reminder_time[$first['times']];
        }else{
            $first['time_slot'] = array();
        }
        if($second['times']){
            $second['time_slot'] = $reminder_time[$second['times']];
        }else{
            $second['time_slot'] = array();
        }
        if($third['times']){
            $third['time_slot'] = $reminder_time[$third['times']];
        }else{
            $third['time_slot'] = array();
        }
        if($fourth['times']){
            $fourth['time_slot'] = $reminder_time[$fourth['times']];
        }else{
            $fourth['time_slot'] = array();
        }

        $reminder = [$first,$second,$third,$fourth];

        // 保存设置
        settings()->set('reminder',$reminder);
        settings()->set('reminder_time',$reminder_time);

        return true;
    }
}
