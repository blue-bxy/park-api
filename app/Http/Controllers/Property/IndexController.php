<?php

namespace App\Http\Controllers\Property;

use App\Http\Controllers\App\BaseController;
use App\Models\Dmanger\CarRent;
use App\Models\Dmanger\CarStop;
use App\Models\Parks\ParkBluetooth;
use App\Models\Parks\ParkCamera;
use App\Models\Parks\ParkSpace;
use App\Models\Parks\ParkSpaceLock;
use App\Models\Property;
use App\Models\Users\UserOrder;
use App\Services\PropertyIndexService;
use Illuminate\Http\Request;

class IndexController extends BaseController
{

    public function index(Request $request, PropertyIndexService $service)
    {

        // 顶部数据
        $carport = $service->carport($request);

        // 车流量统计
        $traffic = $service->traffic($request);

        // 出租车位分析
        $rent = $service->rent($request);

        // 实时车场状态
        $park = $service->state($request);

        // 财务趋势分析
        $finance = $service->finance($request);

        // 车位统计
        $spaces = $service->spaces($request);

        // 设备故障
        $devices = $service->devices($request);

        // 消息推送
        $msg = $service->msg($request);

        return $this->responseData(compact('carport','traffic','rent','park','finance','spaces','devices','msg'));
    }

//    public function parkId(Request $request)
//    {
//        $admin_id = ($request->user())->id;
//
//        return Property::where('id',$admin_id)->value('park_id');
//    }

    /**
     * 顶部接口（预约车位、车位总数、约租车、临时车）
     * @param Request $request
     */
//    public function top(Request $request)
//    {
//
//        $park_id = $this->parkId($request);
//
//        // 车位
//
//        $status = [ParkSpace::STATUS_RESERVING,ParkSpace::STATUS_RESERVED];
//
//        $apt = ParkSpace::where('park_id',$park_id)->whereIn('status',$status)->count();
//
//        $all_space = ParkSpace::where('park_id',$park_id)->count();
//
//        // 停车
//
//        $rent = CarStop::where(['park_id'=>$park_id,'car_type'=>CarStop::CAR_RENT])->count();
//
//        $all_stop = CarStop::where('park_id',$park_id)->count();
//
//        $arr = ['apt'=>$apt,'all_space'=>$all_space,'rent'=>$rent,'all_stop'=>$all_stop];
//
//        return $this->responseData($arr);
//    }

    /**
     * 车流量统计
     * @param Request $request
     */
//    public function traffic(Request $request)
//    {
//        $park_id = $this->parkId($request);
//
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
//              CarStop::where('park_id',$park_id)->whereDate('car_in_time',date('Y-m-d',$date))->count();
//
//            $arr['car_out'][date('m-d',$date)] = CarStop::where('park_id',$park_id)->whereDate('car_out_time',date('Y-m-d',$date))->count();
//
//            $arr['car_all'][date('m-d',$date)] =  $arr['car_in'][date('m-d',$date)] +  $arr['car_out'][date('m-d',$date)];
//
//            $date -= $time;
//        }
//
//        $date = null;   // 清空静态变量
//
//        return $this->responseData($arr);
//
//    }

    /**
     * 出租车位
     * @param Request $request
     */
//    public function rent(Request $request)
//    {
//        $park_id = $this->parkId($request);
//
//        $time = 86400;
//
//        static $da;  // 定义静态常量
//
//        $da = strtotime(now()->format('Y-m-d'));  // 当前日期得时间戳
//
//        $arr = array();
//
//        for($i=1; $i<8; $i++){
//
//            $arr['all_space'][date('m-d',$da)] = CarRent::where(['park_id'=>$park_id,])->whereDate('rent_end_time','<',date('Y-m-d',$da))->count(); // 出租车位数
//
//            $arr['all_price'][date('m-d',$da)] = CarRent::where(['park_id'=>$park_id,])->whereDate('rent_end_time','<',date('Y-m-d',$da))->sum('rent_all_price');   // 出租车位金额
//
//            $query = CarRent::query();
//            $query->with('carApt');
//            $query->where(['park_id'=>$park_id,]);
//            $query ->whereDate('rent_end_time','<',date('Y-m-d',$da));
//            $arr['all_order'][date('m-d',$da)] = $query->count();
//
//            $da -= $time;
//        }
//
//        $da = null;
//
//        return $this->responseData($arr);
//    }

    /**
     * 实时车场状态
     * @param Request $request
     */
//    public function state(Request $request)
//    {
//        $park_id = $this->parkId($request);
//
//        $date = now()->format('n');
//
//        // 金额
//        $all_amount = UserOrder::where(['park_id'=>$park_id,'status'=>'paid'])->whereDate('paid_at',$date)->sum('total_amount');    // 总金额
//        $discount_amount = UserOrder::where('park_id',$park_id)->whereDate('paid_at',$date)->sum('discount_amount');    // 总的优惠金额
//        $actual_amount = UserOrder::where(['park_id'=>$park_id,'status'=>'paid'])->whereDate('paid_at',$date)->sum('total_amount');    // 实收金额
//        $failed_amount = UserOrder::where(['park_id'=>$park_id,'status'=>'failed'])->whereDate('paid_at',$date)->sum('total_amount');    // 异常金额，支付失败
//
//        $arr['amount'] = ['all_amount'=>$all_amount,'discount_amount'=>$discount_amount,'actual_amount'=>$actual_amount,'failed_amount'=>$failed_amount];
//
//        // 支付方式
//        $ali_pay = UserOrder::where(['payment_gateway'=>'ali_app','status'=>'paid'])->whereDate('paid_at',$date)->sum('amount');  // 支付宝
//        $wx_pay = UserOrder::where(['payment_gateway'=>'wx_app','status'=>'paid'])->whereDate('paid_at',$date)->sum('amount');  // 微信
//        $balance_pay = UserOrder::where(['payment_gateway'=>'balance','status'=>'paid'])->whereDate('paid_at',$date)->sum('amount');  // 余额
//
//        $arr['payment'] =['ali_pay'=>$ali_pay,'wx_pay'=>$wx_pay,'balance_pay'=>$balance_pay];
//
//        // 车辆类型
//        $temp = CarStop::where(['car_type'=>1,'park_id'=>$park_id])->whereDate('car_in_time',$date)->count();  // 临时车
//        $rent = CarStop::where(['car_type'=>2,'park_id'=>$park_id])->whereDate('car_in_time',$date)->count();  // 月租车
//        $vip = CarStop::where(['car_type'=>3,'park_id'=>$park_id])->whereDate('car_in_time',$date)->count();  // vip
//        $free = CarStop::where(['car_type'=>4,'park_id'=>$park_id])->whereDate('car_in_time',$date)->count();  // 特殊车辆
//
//        $arr['car_type'] = ['temp'=>$temp,'rent'=>$rent,'vip'=>$vip,'free'=>$free];
//
//        return $this->responseData($arr);
//    }

    /**
     *  财务趋势
     * @param Request $request
     */
//    public function finance(Request $request)
//    {
//
//        $park_id = $this->parkId($request);
//
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
//
//        return $this->responseData($arr);
//    }

    /**
     * 首页车位统计
     * @return \Illuminate\Http\JsonResponse
     */
//    public function spaces(Request $request) {
//        $spaces = ParkSpace::query()
//            ->where('park_id', '=', $request->user()->park_id)
//            ->get();
//        $total = $spaces->count();
//        $used = 0;
//        $unused = 0;
//        $disabled = 0;
//        $fixed = [
//            'used' => 0,
//            'unused' => 0
//        ];
//        $temp = [
//            'used' => 0,
//            'unused' => 0
//        ];
//        $charging = [
//            'used' => 0,
//            'unused' => 0
//        ];
//        foreach ($spaces as $space) {
//            if ($space->status == ParkSpace::STATUS_DISABLED) {
//                $disabled++;
//            } elseif ($space->status == ParkSpace::STATUS_PARKING) {
//                $used++;
//                if ($space->type == ParkSpace::TYPE_FIXED) {
//                    $fixed['used']++;
//                } elseif ($space->type == ParkSpace::TYPE_TEMP) {
//                    $temp['used']++;
//                }
//                if ($space->category == ParkSpace::CATEGORY_CHARGING_PILE) {
//                    $charging['used']++;
//                }
//            } else {
//                $unused++;
//                if ($space->type == ParkSpace::TYPE_FIXED) {
//                    $fixed['unused']++;
//                } elseif ($space->type == ParkSpace::TYPE_TEMP) {
//                    $temp['unused']++;
//                }
//                if ($space->category == ParkSpace::CATEGORY_CHARGING_PILE) {
//                    $charging['unused']++;
//                }
//            }
//        }
//        return $this->responseData(compact('total', 'used', 'unused', 'disabled', 'fixed', 'temp', 'charging'));
//    }

    /**
     * 首页设备（故障）统计
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
//    public function devices(Request $request) {
//        $space_cameras = ParkCamera::query()
//            ->where('park_id', '=', $request->user()->park_id)
//            ->where('status', '=', ParkCamera::STATUS_ERROR)
//            ->where('monitor_type', '=', ParkCamera::MONITOR_TYPE_SPACE)
//            ->count();
//        $entrance_cameras = ParkCamera::query()
//            ->where('park_id', '=', $request->user()->park_id)
//            ->where('status', '=', ParkCamera::STATUS_ERROR)
//            ->where('monitor_type', '=', ParkCamera::MONITOR_TYPE_ENTRANCE)
//            ->count();
//        $bluetooths = ParkBluetooth::query()
//            ->where('park_id', '=', $request->user()->park_id)
//            ->where('status', '=', ParkBluetooth::STATUS_ERROR)
//            ->count();
//        $locks = ParkSpaceLock::query()
//            ->where('park_id', '=', $request->user()->park_id)
//            ->where('status', '=', ParkSpaceLock::STATUS_ERROR)
//            ->count();
//        return $this->responseData(compact('space_cameras', 'entrance_cameras', 'bluetooths', 'locks'));
//    }
}
