<?php


namespace App\Services;


use App\Models\Dmanger\CarApt;
use App\Models\Dmanger\CarAptOrder;
use App\Models\Dmanger\CarRent;
use App\Models\Dmanger\CarStop;
use App\Models\Dmanger\CarStopOrder;
use App\Models\Parks\Park;
use App\Models\Parks\ParkBluetooth;
use App\Models\Parks\ParkCamera;
use App\Models\Parks\ParkSpaceLock;
use App\Models\User;
use App\Models\Users\UserOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class CarportDataService
{
    // 运维统计
    public function opsTotal()
    {
        $project_total = Park::query()->count();
        // 异常
        $project_error_total = Park::query()->where('park_operation_state', 3)->count();

        // 设备：蓝牙、摄像头、地锁
        $device_total = 0;

        $device_total += ParkBluetooth::count();
        $device_total += ParkCamera::count();
        $device_total += ParkSpaceLock::count();

        $device_error_total = 0;

        $device_error_total += ParkBluetooth::query()->where('network_status', 2)->count();
        $device_error_total += ParkCamera::query()->where('network_status', 2)->count();
        $device_error_total += ParkSpaceLock::query()->where('network_status', 2)->count();

        return compact('project_total', 'project_error_total', 'device_total', 'device_error_total');
    }

    // 运维报修
    public function opsRepair()
    {
        $days = $this->mockRepairData();

        $total = rand(10, 30);
        $online = rand(0, 15);

        $total = [
            // 报修项目、待处理项目、已处理项目
            'repair_total' => $total,
            'repair_online' => $online,
            'repair_finish' => $total - $online
        ];

        return compact('total', 'days');
    }

    // 停车统计
    public function stop()
    {
        // 总额、历史总额、本月时长、历史时长
        $month = now()->format('Y-m');

        $query = UserOrder::query()
            ->whereIn('status', [
                UserOrder::ORDER_STATE_PAID,
                UserOrder::ORDER_STATE_FINISHED,
                UserOrder::ORDER_STATE_COMMENTED
            ]);

        $total_amount = (int) $query->sum('amount');

        $amount =  $query->whereRaw("date_format(created_at, '%Y-%m') = '$month'")
            ->sum('amount');

        $subscribe_total = CarStop::query()->sum('stop_time');
        $subscribe = CarStop::query()
            ->whereRaw("date_format(created_at, '%Y-%m') = '$month'")
            ->sum('stop_time');

        return compact('total_amount', 'amount', 'subscribe_total', 'subscribe');
    }

    // 订单统计
    public function order()
    {
        // 已完成、已失败、已取消
        $dates = $this->getOrderDateData(now());

        $month = $this->getOrderMonthData(now());

        return compact('month', 'dates');
    }

    protected function getOrderDateData($date)
    {
        $query = UserOrder::query();

        $query->selectSubStatus();

        $query->whereYear("created_at", $date);
        $query->whereMonth("created_at", $date);

        $query->selectSub("date_format(created_at, '%Y-%m-%d')", 'date');

        $query->groupByRaw("date_format(created_at, '%Y-%m-%d')");

        return $query->get();
    }

    protected function getOrderMonthData($month)
    {
        $query = UserOrder::query();

        $query->selectSubStatus();

        $query->whereYear("created_at", $month);
        $query->whereMonth("created_at", $month);

        $query->selectSub("date_format(created_at, '%Y-%m')", 'month');

        return $query->first();
    }

    protected function mockRepairData()
    {
        $day = Carbon::yesterday()->subDays(7);

        $data = [];
        for ($i = 1; $i <= 7; $i ++) {
            $time = $day->addDays(1);

            $total = rand(10, 20);
            $online = rand(0, 10);
            $data[$time->toDateString()] = [
                // 报修项目、待处理项目、已处理项目
                'repair_total' => $total,
                'repair_online' => $online,
                'repair_finish' => $total - $online
            ];
        }

        return $data;
    }

    // 预约统计
    public function reservation()
    {
        // 本月
        $month = now()->format('Y-m');

        $amount = CarApt::query()
                ->whereRaw("date_format(created_at, '%Y-%m') = '$month'")
                ->sum('deduct_amount'); // 本月预约总额

        $total_amount = CarApt::query()->sum('deduct_amount');  // 历史预约总额

        $subscribe = CarApt::query()
            ->whereRaw("date_format(created_at, '%Y-%m') = '$month'")
            ->sum('apt_time');  // 本月预约时长

        $subscribe_total = CarApt::query()->sum('apt_time'); // 历史预约时长

        return compact('amount','total_amount','subscribe','subscribe_total');

    }

    // 出租车位分析
    public function rent()
    {

        // 查询近七个月的数据，这是查询的起始时间
        $start = date('Y-m',strtotime('-6 month',strtotime(date('Y-m-01',time()))));

        $end = date("Y-m",strtotime(now()));

        // 出租车位总数和价格
        $m = ['01'=>'1','02'=>'2','03'=>'3','04'=>'4','05'=>'5','06'=>'6','07'=>'7','08'=>'8','09'=>'9','10'=>'10','11'=>'11','12'=>'12'];

        $arr1 = array();

        for($i=0; $i<7; $i++){

            $date = date('m',strtotime("-{$i} month",strtotime(date('Y-m-01',time()))));

            $arr1[$date] = [
                'count'=>0,
                'month' => $m[$date]
            ];

            $rent_count_amount[$date] = CarRent::query()
                ->select('park_space_id')
                ->whereRaw("date_format(created_at, '%m') <='{$date}'")
                ->distinct()
                ->count('park_space_id');
        }

       krsort($arr1);

        foreach ($rent_count_amount as $k1=>$v){
            foreach ($arr1 as $k=>$vo){
                if($k1==$k){
                    $arr1[$k]['count'] = $v;
                }
            }
        }

        // 订单
        $rent_order = CarApt::query()
            ->where('car_rent_id','!=','')
            ->whereRaw("date_format(created_at, '%Y-%m') >='{$start}' and date_format(created_at, '%Y-%m') <= '{$end}'")
            ->selectSub("count(created_at)",'apt_order')
            ->selectSub("sum(deduct_amount)",'amount')
            ->selectSub("date_format(created_at, '%m')", 'month')
            ->groupByRaw("date_format(created_at, '%Y-%m')")
            ->get();

        $arr2 = array();

        for($i=0; $i<7; $i++){

            $date = date('m',strtotime("-{$i} month",strtotime(date('Y-m-01',time()))));

            $arr2[$date] = [
                'apt_order' => 0,
                'amount' => 0,
                'month' => $m[$date]
            ];
        }

        krsort($arr2);

        foreach ($rent_order as $v){
            foreach ($arr2 as $k=>$vo){
                if($v['month']==$k){
                    $arr2[$k]['apt_order'] = $v['apt_order'];
                    $arr2[$k]['amount'] = $v['amount'];
                }
            }
        }

        $rent = array();

        foreach ($arr1 as $k=>$v){
            $rent[$m[$k]] = [
                'count' => $arr1[$k]['count'],
                'apt_order' => $arr2[$k]['apt_order'],
                'amount' => $arr2[$k]['amount'],
                'month' => $arr1[$k]['month']
            ];
        }

        return compact('rent');
    }

    // 用户统计
    public function user()
    {
        $start = date('Y-m',strtotime('-6 month',strtotime(date('Y-m-01',time()))));

        $end = date("Y-m",strtotime(now()));
        // 注册人数
        $register = User::query()
                    ->whereRaw("date_format(created_at, '%Y-%m') >='{$start}' and date_format(created_at, '%Y-%m') <= '{$end}'")
                    ->selectSub("count(created_at)",'register')
                    ->selectSub("date_format(created_at, '%m')", 'month')
                    ->groupByRaw("date_format(created_at, '%Y-%m')")
                    ->get();

        $m = ['01'=>'1','02'=>'2','03'=>'3','04'=>'4','05'=>'5','06'=>'6','07'=>'7','08'=>'8','09'=>'9','10'=>'10','11'=>'11','12'=>'12'];

        $arr1 = array();

        for($i=0; $i<7; $i++){

            $date = date('m',strtotime("-{$i} month",strtotime(date('Y-m-01',time()))));

            $arr1[$date] = [
                'register' => 0,
                'month' => $m[$date]
            ];
        }

        krsort($arr1);

        foreach ($register as $v){
            foreach ($arr1 as $k=>$vo){
                if($v['month']==$k){
                    $arr1[$k]['register'] = $v['register'];
                }
            }
        }
;
        // 活跃人数
        $active = User::query()
            ->whereRaw("date_format(created_at, '%Y-%m') >='{$start}' and date_format(created_at, '%Y-%m') <= '{$end}'")
            ->selectSub("count(updated_at)",'active')
            ->selectSub("date_format(updated_at, '%m')", 'month')
            ->groupByRaw("date_format(updated_at, '%Y-%m')")
            ->get();

        $arr2 = array();

        for($i=0; $i<7; $i++){

            $date = date('m',strtotime("-{$i} month",strtotime(date('Y-m-01',time()))));

            $arr2[$date] = [
                'active' => 0,
                'month' => $m[$date]
            ];
        }

        krsort($arr2);

        foreach ($active as $v){
            foreach ($arr2 as $k=>$vo){
                if($v['month']==$k){
                    $arr2[$k]['active'] = $v['active'];
                }
            }
        }

        $user = array();

        foreach ($arr1 as $k=>$v){
            $user[$m[$k]] = [
                'register'=>$arr1[$k]['register'],
                'active'=>$arr2[$k]['active'],
                'month'=>$arr1[$k]['month']
            ];
        }

        return compact('user');

    }

    // 财务趋势分析
    public function finance()
    {
        $date = now();

        // 支付方式统计
        $pay = ['ali'=>'ali_app','wx'=>'wx_app','ba'=>'balance'];

        $apt = array();
        $stop = array();

        foreach ($pay as $k=>$v){

           $apt[$k] =  CarAptOrder::query()
                ->whereYear("paid_at", $date)
                ->whereMonth("paid_at", $date)
                ->where('payment_gateway',$v)
                ->where('status','paid')
                ->sum('amount');

           $stop[$k] =  CarStopOrder::query()
                ->whereYear("paid_at", $date)
                ->whereMonth("paid_at", $date)
                ->where('payment_gateway',$v)
                ->where('status','paid')
                ->sum('amount');

        }

        foreach ($apt as $k=> $v){
            foreach ($stop as $vo){
                $payment[$k] = $v + $vo;
            }
        }

        // 收费类型
        $vehicle= ['temp'=>0,'rent'=>1,'vip'=>2,'special'=>7];

        foreach ($vehicle as $k=>$v){
            $count[$k] = CarStop::query()
                ->whereYear("created_at", $date)
                ->whereMonth("created_at", $date)
                ->where('car_type',$v)
                ->count();
        }

        // 金额统计，停车金额、出租金额、预约金额
        $start = date('Y-m',strtotime('-6 month',strtotime(date('Y-m-01',time()))));

        $end = date("Y-m",strtotime(now()));

            // 停车
        $stop_amount = UserOrder::query()
                ->whereRaw("date_format(created_at, '%Y-%m') >='{$start}' and date_format(created_at, '%Y-%m') <= '{$end}'")
                ->where('status','paid')
                ->selectSub("sum(amount)",'stop_amount')
                ->selectSub("date_format(created_at, '%m')", 'month')
                ->groupByRaw("date_format(created_at, '%Y-%m')")
                ->get();

        $m = ['01'=>'1','02'=>'2','03'=>'3','04'=>'4','05'=>'5','06'=>'6','07'=>'7','08'=>'8','09'=>'9','10'=>'10','11'=>'11','12'=>'12'];

        $arr1 = array();

        for($i=0; $i<7; $i++){

            $date =  date('m',strtotime("-{$i} month",strtotime(date('Y-m-01',time()))));

            $arr1[$date] = [
                'stop_amount' => 0,
                'rent_amount' => 0,
                'deduct_amount' => 0,
                'month' => $m[$date]
            ];
        }

        krsort($arr1);     // 降序排序

        foreach ($stop_amount as $v){
            foreach ($arr1 as $k=>$vo){
                if($v['month']==$k){
                    $arr1[$k]['stop_amount'] = $v['stop_amount'];
                }
            }
        }
            // 出租
        $rent_amount =  CarApt::query()
            ->where('car_rent_id','!=','')
            ->whereRaw("date_format(created_at, '%Y-%m') >='{$start}' and date_format(created_at, '%Y-%m') <= '{$end}'")
            ->selectSub("sum(deduct_amount)",'rent_amount')
            ->selectSub("date_format(created_at, '%m')", 'month')
            ->groupByRaw("date_format(created_at, '%Y-%m')")
            ->get();

        foreach ($rent_amount as $v){
            foreach ($arr1 as $k=>$vo){
                if($v['month']==$k){
                    $arr1[$k]['rent_amount'] = $v['rent_amount'];
                }
            }
        }

            // 预约
        $apt_amount = CarApt::query()
            ->whereRaw("date_format(created_at, '%Y-%m') >='{$start}' and date_format(created_at, '%Y-%m') <= '{$end}'")
            ->selectSub("sum(deduct_amount)",'deduct_amount')
            ->selectSub("date_format(created_at, '%m')", 'month')
            ->groupByRaw("date_format(created_at, '%Y-%m')")
            ->get();


        foreach ($apt_amount as $v){
            foreach ($arr1 as $k=>$vo){
                if($v['month']==$k){
                    $arr1[$k]['deduct_amount'] = $v['deduct_amount'];
                }
            }
        }

        $finance = array();

        foreach ($arr1 as $k=>$v){
            $finance[$m[$k]] = [
                'stop_amount'=>$arr1[$k]['stop_amount'],
                'rent_amount'=>$arr1[$k]['rent_amount'],
                'deduct_amount'=>$arr1[$k]['deduct_amount'],
                'month' => $arr1[$k]['month']
            ];
        }

        return compact('payment','count','finance');

    }

    // 车流量分析
    public function flux()
    {
        $start = date('Y-m',strtotime('-6 month',strtotime(date('Y-m-01',time()))));

        $end = date("Y-m",strtotime(now()));

        $flux = CarStop::query()
                ->whereRaw("date_format(created_at, '%Y-%m') >='{$start}' and date_format(created_at, '%Y-%m') <= '{$end}'")
                ->selectSub("count(created_at)",'count')
                ->selectSub("date_format(created_at, '%m')", 'month')
                ->groupByRaw("date_format(created_at, '%Y-%m')")
                ->get();

        $m = ['01'=>'1','02'=>'2','03'=>'3','04'=>'4','05'=>'5','06'=>'6','07'=>'7','08'=>'8','09'=>'9','10'=>'10','11'=>'11','12'=>'12'];

        $arr1 = array();

        for($i=0; $i<7; $i++){

            $date =  date('m',strtotime("-{$i} month",strtotime(date('Y-m-01',time()))));

            $arr1[$date] = [
                'count' => 0,
                'month' => $m[$date]
            ];

        }

        krsort($arr1);

        foreach ($flux as $v){
            foreach ($arr1 as $k=>$vo){
                if($v['month']==$k){
                    $arr1[$k]['count'] = $v['count'];
                }
            }
        }

        return compact('arr1');
    }

    // 对应区的数量
    public function areaNum()
    {

        $arr = [
                    [
                        'name' => "崇明区",
                        'value' => "166",
                    ],
                    [
                        'name' => "宝山区",
                        'value'=> "210"
                    ],
                    [
                        'name' => "嘉定区",
                        'value' => "320"
                    ],
                    [
                        'name' => "青浦区",
                        'value' => "221"
                    ],
                    [
                        'name' => "杨浦区",
                        'value' => "80"
                    ],
                    [
                        'name' => "虹口区",
                        'value' => "130"
                    ],
                    [
                        'name' => "普陀区",
                        'value' => "0"
                    ],
                    [
                        'name' => "静安区",
                        'value' => "60"
                    ],
                    [
                        'name' => "黄浦区",
                        'value' => "30"
                    ],
                    [
                        'name' => "长宁区",
                        'value' => "67"
                    ],
                    [
                        'name' => "徐汇区",
                        'value' => "30"
                    ],
                    [
                        'name' => "浦东新区",
                        'value' => "550"
                    ],
                    [
                        'name' => "松江区",
                        'value' => "330"
                    ],
                    [
                        'name' => "闵行区",
                        'value' => "0"
                    ],
                    [
                        'name' => "奉贤区",
                        'value' => "402"
                    ],
                    [
                        'name' => "金山区",
                        'value' => "72"
                    ]
        ];

        $query = Park::query()
                ->where(['park_province'=>'上海市','park_city'=>'市辖区'])
                ->selectSub("park_area", 'name')
                ->selectSub("count(id)",'value')
                ->groupBy("park_area")
                ->get();

        foreach ($arr as $k=>$v){
            foreach ($query as $vo){
                if($v['name'] == $vo['name']){
                    $arr[$k]['value'] = $vo['value'];
                }
            }
        }

        return compact('arr');
    }
}
