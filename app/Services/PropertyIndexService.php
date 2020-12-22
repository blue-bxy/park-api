<?php

namespace App\Services;


use App\Models\Dmanger\CarApt;
use App\Models\Dmanger\CarRent;
use App\Models\Dmanger\CarStop;
use App\Models\Parks\Park;
use App\Models\Parks\ParkBluetooth;
use App\Models\Parks\ParkCamera;
use App\Models\Parks\ParkSpace;
use App\Models\Parks\ParkSpaceLock;
use App\Models\Property;
use App\Models\Users\PropertyMessage;
use App\Models\Users\UserOrder;
use App\Models\Users\UserParkingSpace;
use function foo\func;
use Illuminate\Http\Request;

class PropertyIndexService
{

    // 车场
    public function parkId(Request $request)
    {
        return ($request->user())->park_id;
    }

    /**
     * 顶部接口（预约车位、车位总数、约租车、临时车）
     * @param Request $request
     */
    public function carport(Request $request)
    {

        $park_id = $this->parkId($request);

        // 车位

        $status = [ParkSpace::STATUS_RESERVING,ParkSpace::STATUS_RESERVED];

        $apt = ParkSpace::query()->where('park_id',$park_id)->whereIn('status',$status)->count();

        $all_space = ParkSpace::query()->where('park_id',$park_id)->count();

        // 停车
            // 当前日期

        $date = date('Y-m-d');

            // 月租车
        $rent = CarStop::query()
            ->where(['park_id'=>$park_id,'car_type'=>CarStop::CAR_TYPE_RENT])
            ->whereRaw("date_format('car_in_time','%Y-%m-%d') = {$date}")
            ->count();

            // 临时车
        $all_stop = CarStop::query()
            ->where(['park_id'=>$park_id,'car_type'=>CarStop::CAR_TYPE_TEMP])
            ->whereRaw("date_format('car_in_time','%Y-%m-%d') = {$date}")
            ->count();

        $arr = ['apt'=>$apt,'all_space'=>$all_space,'rent'=>$rent,'all_stop'=>$all_stop];

        return compact('arr');
    }

    /**
     * 车流量统计
     * @param Request $request
     */
    public function traffic(Request $request)
    {
        $park_id = $this->parkId($request);

//        $time = 86400;
//
//        static $date;  // 定义静态常量
//
//        $date = strtotime(now()->format('Y-m-d'));  // 当前日期得时间戳
//
//        $day = now()->format('j');
//
//        $arr = array();
//
//        for ($day;$day>=1;$day--){
//
//            CarStop::query()->where('park_id',$park_id)->whereDate('car_in_time',date('Y-m-d',$date))->count();
//
//            $arr['car_out'][date('m-d',$date)] = CarStop::query()->where('park_id',$park_id)->whereDate('car_out_time',date('Y-m-d',$date))->count();
//
//            $arr['car_all'][date('m-d',$date)] =  $arr['car_in'][date('m-d',$date)] +  $arr['car_out'][date('m-d',$date)];
//
//            $date -= $time;
//        }
//
//        $date = null;   // 清空静态变量

        $start = date('Y-m-d',strtotime('-15 day'));

        $end = date("Y-m-d",strtotime(now()));

        $arr1 = array();

        for($i=0; $i<15; $i++){

            $arr1[date('Y-m-d',strtotime("-{$i} day"))] = [
                'car_in' => 0,
                'car_out' => 0,
                'all' => 0,
                'date' => date('n-j',strtotime("-{$i} day"))
            ];
        }

        $arr1 = array_reverse($arr1);

        $car_in = CarStop::query()
                ->where('park_id',$park_id)
                ->whereRaw("date_format(car_in_time, '%Y-%m-%d') >='{$start}' and date_format(car_in_time, '%Y-%m-%d') <= '{$end}'")
                ->selectSub("count(car_in_time)", 'car_in')
                ->selectSub("date_format(car_in_time, '%Y-%m-%d')", 'date')
                ->groupByRaw("date_format(car_in_time, '%Y-%m-%d')")
                ->get();

        $car_out = CarStop::query()
            ->where('park_id',$park_id)
            ->whereRaw("date_format(car_out_time, '%Y-%m-%d') >='{$start}' and date_format(car_out_time, '%Y-%m-%d') <= '{$end}'")
            ->selectSub("count(car_out_time)", 'car_out')
            ->selectSub("date_format(car_out_time, '%Y-%m-%d')", 'date')
            ->groupByRaw("date_format(car_out_time, '%Y-%m-%d')")
            ->get();

        foreach ($arr1 as $k=>$vo){
            foreach ($car_in as $v){
                foreach ($car_out as $v1){
                    if($v['date']==$k){
                        $arr1[$k]['car_in'] = $v['car_in'];
                        $arr1[$k]['car_out'] = $v1['car_out'];
                        $arr1[$k]['all'] = $v['car_in'] + $v1['car_out'];
                    }
                }
            }
        }

        return compact('arr1');
    }


    /**
     * 出租车位
     * @param Request $request
     */
    public function rent(Request $request)
    {
        $park_id = $this->parkId($request);

//        $time = 86400;
//
//        static $da;  // 定义静态常量
//
//        $da = strtotime(now()->format('Y-m-d'));  // 当前日期得时间戳
//
//        $arr = array();
//
//        for($i=1; $i<6; $i++){
//
//            $query = CarRent::query();
//            $query->with('carApt');
//            $query->where(['park_id'=>$park_id,]);
//            $query ->whereDate('rent_end_time','<',date('Y-m-d',$da));
//            $arr['all_order'][date('Y-m-d',$da)] = $query->count();
//
//            $da -= $time;
//        }
//
//        $da = null;

        $start = date('Y-m-d',strtotime('-4 day'));

        $end = date("Y-m-d",strtotime(now()));

        $rent= array();

        for($i=0; $i<5; $i++){

            $date = date('Y-m-d',strtotime("-{$i} day"));

            $rent[$date] = [
                'park_space' => 0,
                'amount' => 0,
                'order' => 0,
                'date' =>date('n-j',strtotime("-{$i} day"))
            ];

            // 对应出租的数量
            $arr1[$date] = CarRent::query()
                ->select('park_space_id')
                ->where('park_id',$park_id)
                ->whereRaw("date_format(created_at, '%Y-%m-%d') <='{$date}'")
                ->distinct()
                ->count('park_space_id');
        }

        $rent = array_reverse($rent);

        // 总的数量
//        $all_count = CarRent::query()->where(['rent_status'=>1,'park_id'=>$park_id])->count('id');

        foreach ($rent as $k=>$v){
            foreach ($arr1 as $k1=>$v1){
                if($k == $k1){
                    $rent[$k]['park_space'] = $v1;
                }
            }
        }

        // 出租金额和订单
        $amount = CarApt::query()
            ->where('park_id',$park_id)
            ->where('car_rent_id','!=','')
            ->whereRaw("date_format(created_at, '%Y-%m-%d') >='{$start}' and date_format(created_at, '%Y-%m-%d') <= '{$end}'")
            ->selectSub("sum(deduct_amount)", 'amount')
            ->selectSub("count(id)", 'order')
            ->selectSub("date_format(created_at, '%Y-%m-%d')", 'date')
            ->groupByRaw("date_format(created_at, '%Y-%m-%d')")
            ->get();

        foreach ($rent as $k=>$v){
            foreach ($amount as $v1){
                if($k == $v1['date']){
                    $rent[$k]['amount'] = $v1['amount'];
                    $rent[$k]['order'] = $v1['order'];
                }
            }
        }

        return compact('rent');
    }

    /**
     * 实时车场状态
     * @param Request $request
     */
    public function state(Request $request)
    {
        $park_id = $this->parkId($request);

        $date = now()->format('n');

        // 金额
        $all_amount = UserOrder::where(['park_id'=>$park_id])->where('status','!=','cancelled')->whereDate('paid_at',$date)->sum('total_amount');    // 总金额
        $discount_amount = UserOrder::where('park_id',$park_id)->whereDate('paid_at',$date)->sum('discount_amount');    // 总的优惠金额
        $actual_amount = UserOrder::where(['park_id'=>$park_id])->where('status','!=','cancelled')->whereDate('paid_at',$date)->sum('total_amount');    // 实收金额
        $failed_amount = UserOrder::where(['park_id'=>$park_id])->where('status','!=','cancelled')->whereDate('paid_at',$date)->sum('total_amount');    // 异常金额，支付失败

        $arr['amount'] = ['all_amount'=>$all_amount,'discount_amount'=>$discount_amount,'actual_amount'=>$actual_amount,'failed_amount'=>$failed_amount];


        // 支付方式
        $ali_pay = UserOrder::where(['payment_gateway'=>'ali_app'])->where('status','!=','cancelled')->whereDate('paid_at',$date)->sum('amount');  // 支付宝
        $wx_pay = UserOrder::where(['payment_gateway'=>'wx_app'])->where('status','!=','cancelled')->whereDate('paid_at',$date)->sum('amount');  // 微信
        $balance_pay = UserOrder::where(['payment_gateway'=>'balance'])->where('status','!=','cancelled')->whereDate('paid_at',$date)->sum('amount');  // 余额

        $arr['payment'] =['ali_pay'=>$ali_pay,'wx_pay'=>$wx_pay,'balance_pay'=>$balance_pay];

        // 车辆类型
        $temp = CarStop::where(['car_type'=>CarStop::CAR_TYPE_TEMP,'park_id'=>$park_id])->whereDate('car_in_time',$date)->count();  // 临时车
        $rent = CarStop::where(['car_type'=>CarStop::CAR_TYPE_RENT,'park_id'=>$park_id])->whereDate('car_in_time',$date)->count();  // 月租车
        $vip = CarStop::where(['car_type'=>CarStop::CAR_TYPE_VIP,'park_id'=>$park_id])->whereDate('car_in_time',$date)->count();  // vip
        $free = CarStop::where(['car_type'=>CarStop::CAR_TYPE_SPECIAL,'park_id'=>$park_id])->whereDate('car_in_time',$date)->count();  // 特殊车辆

        $arr['car_type'] = ['temp'=>$temp,'rent'=>$rent,'vip'=>$vip,'free'=>$free];

        return compact('arr');
    }

    /**
     *  财务趋势
     * @param Request $request
     */
    public function finance(Request $request)
    {

        $park_id = $this->parkId($request);

//        $time = 86400;
//
//        static $dat;
//
//        $dat = strtotime(now()->format('Y-m-d'));
//
//        $arr = array();
//
//        for($i=1; $i<8; $i++){
//
//            $arr[date('m-d',$dat)] = UserOrder::where(['park_id'=>$park_id,'status'=>'paid_at'])->whereDate('paid_at',date('Y-m-d',$dat))->sum('total_amount');// 实收金额
//
//            $dat -= $time;
//        }
//
//        $dat =null;

        $start = date('Y-m-d',strtotime('-5 day'));

        $end = date("Y-m-d",strtotime(now()));

        $arr1 = array();

        for($i=0; $i<5; $i++){

            $arr1[date('m-d',strtotime("-{$i} day"))] = [
                'amount' => 0,
                'date' => date('n-j',strtotime("-{$i} day"))
            ];
        }

        $arr1 = array_reverse($arr1);

        $amount = UserOrder::query()
            ->where(['park_id'=>$park_id])
            ->where('status','!=','cancelled')
            ->orWhere('status','!=','pending')
            ->whereRaw("date_format(created_at, '%Y-%m-%d') >='{$start}' and date_format(created_at, '%Y-%m-%d') <= '{$end}'")
            ->selectSub("sum(total_amount)", 'amount')
            ->selectSub("date_format(created_at,'%m-%d')", 'date')
            ->groupByRaw("date_format(created_at, '%Y-%m-%d')")
            ->get();

        foreach ($arr1 as $k=>$v){
            foreach ($amount as $v1){
                if($k == $v1['date']){
                    $arr1[$k]['amount'] = $v1['amount'];
                }
            }
        }

        return compact('arr1');
    }

    /**
     * 首页车位统计
     * @return \Illuminate\Http\JsonResponse
     */
    public function spaces(Request $request) {
        $spaces = ParkSpace::query()
            ->where('park_id', '=', $request->user()->park_id)
            ->get();
        $total = $spaces->count();
        $used = 0;
        $unused = 0;
        $disabled = 0;
        $fixed = [
            'used' => 0,
            'unused' => 0
        ];
        $temp = [
            'used' => 0,
            'unused' => 0
        ];
        $charging = [
            'used' => 0,
            'unused' => 0
        ];
        foreach ($spaces as $space) {
            if ($space->status == ParkSpace::STATUS_DISABLED) {
                $disabled++;
            } elseif ($space->status == ParkSpace::STATUS_PARKING) {
                $used++;
                if ($space->type == ParkSpace::TYPE_FIXED) {
                    $fixed['used']++;
                } elseif ($space->type == ParkSpace::TYPE_TEMP) {
                    $temp['used']++;
                }
                if ($space->category == ParkSpace::CATEGORY_CHARGING_PILE) {
                    $charging['used']++;
                }
            } else {
                $unused++;
                if ($space->type == ParkSpace::TYPE_FIXED) {
                    $fixed['unused']++;
                } elseif ($space->type == ParkSpace::TYPE_TEMP) {
                    $temp['unused']++;
                }
                if ($space->category == ParkSpace::CATEGORY_CHARGING_PILE) {
                    $charging['unused']++;
                }
            }
        }
        return compact('total', 'used', 'unused', 'disabled', 'fixed', 'temp', 'charging');
    }

    /**
     * 首页设备（故障）统计
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function devices(Request $request) {
        $space_cameras = ParkCamera::query()
            ->where('park_id', '=', $request->user()->park_id)
            ->where('status', '=', ParkCamera::STATUS_ERROR)
            ->where('monitor_type', '=', ParkCamera::MONITOR_TYPE_SPACE)
            ->count();
        $entrance_cameras = ParkCamera::query()
            ->where('park_id', '=', $request->user()->park_id)
            ->where('status', '=', ParkCamera::STATUS_ERROR)
            ->where('monitor_type', '=', ParkCamera::MONITOR_TYPE_ENTRANCE)
            ->count();
        $bluetooths = ParkBluetooth::query()
            ->where('park_id', '=', $request->user()->park_id)
            ->where('status', '=', ParkBluetooth::STATUS_ERROR)
            ->count();
        $locks = ParkSpaceLock::query()
            ->where('park_id', '=', $request->user()->park_id)
            ->where('status', '=', ParkSpaceLock::STATUS_ERROR)
            ->count();
        return compact('space_cameras', 'entrance_cameras', 'bluetooths', 'locks');
    }

    /**
     * 消息推送
     * @param Request $request
     */
    public function msg(Request $request)
    {
        $park_id = $this->parkId($request);

        $park = Park::query();

        $data = $park->where('id',$park_id)->first();

        $park_type = $data['park_type'];

        $park_property= $data['park_property'];

        $pro_msg = PropertyMessage::query();

        $msg = $pro_msg->where(['park_type' => $park_type,'park_property' => $park_property])->orderBy('created_at','desc')->get();

        $content = ($msg->first())['content'];

        return compact('content');

    }
}
