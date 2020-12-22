<?php

namespace App\Http\Controllers\Property;

use App\Http\Resources\Property\PropertyBalanceResource;
use App\Models\Bills\OrderAmountDivide;
use App\Models\Bills\ParkWallet;
use App\Models\Bills\ParkWalletBalance;
use App\Models\Dmanger\CarApt;
use App\Models\Financial\AccountManage;
use App\Models\Financial\BookingFee;
use App\Models\Financial\Withdrawal;
use App\Models\Parks\Park;
use App\Models\Property;
use App\Models\Withdrawal\AptOrderDay;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class PropertyBalanceController extends BaseController
{
    /**
     * 物业余额
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function amount(Request $request)
    {

        $data = array();

        $admin = $request->user();

        $wallet = $admin->wallet()->first();

        $park_amount = $wallet->amount ?? 0;

        $data['amount'] = $park_amount;

        return $this->responseData($data);
    }

    /**
     * 预约余额管理-列表数据
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', $this->per_page);

        $park_id = ($request->user())->park_id;

        $query = ParkWalletBalance::query();

        $query->where('park_id',$park_id);

        $query->where('type','!=','withdrawal');

        $data = $query->selectSub("sum(amount)", 'amount')
            ->selectSub("date_format(created_at, '%Y-%m-%d')", 'date')
            ->groupByRaw("date_format(created_at, '%Y-%m-%d')")
            ->latest()
            ->paginate($perPage);

        return PropertyBalanceResource::collection($data);
    }

    /**
     * 提现列表数据
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function WithdrawalIndex(Request $request){
        $perPage = $request->input('per_page', $this->per_page);
//        $type=$request->input('type');
        $property=$request->user();
        $park_id=$property->park_id;
        $query=Withdrawal::query();
        $query->with('user');
//        if($time=$request->input('time')){
//            if(intval($type)==4){
//                $start_time=Carbon::parse($time)->startOfDay();
//                $end_time=Carbon::parse($time)->endOfDay();
//                $query->whereBetween('apply_time',[$start_time,$end_time]);
//            }
//        }
        $query->where('park_id',$park_id);
        $data=$query->latest()->paginate($perPage);
        return PropertyBalanceResource::collection($data);
    }

    /**
     * Store a newly created resource in storage.
     *得到该车场收取的总预约费
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $property=$request->user();
        $park_id=$property->park_id;
        $query=Withdrawal::query();
        $query->where('park_id',$park_id);
        $withdrawal=$query->latest()->first('apply_time');
        $end=$withdrawal['apply_time'];
        $map=[];
        if(!empty($end)){
            $map[]=['created_at','>',$end];
        }else{
            $map[]=['created_at','<=',now()];
        }
        $query=OrderAmountDivide::query();
        $query->where("park_id",$park_id);
        $query->where($map);
        $query->where("model_type",CarApt::class);
        $apt_money=$query->sum("park_fee");
        return $apt_money;
    }

    /**
     * 提现操作
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function withdrawal(Request $request)
    {
        $apt_money= $request->input('apt_money');

        if($apt_money < 20000){
            return $this->responseFailed('提现金额必须大于200元');
        }
        $user_id = ($request->user())->id;
        $park_id = ($request->user())->park_id;
        $account_manages=AccountManage::where('park_id',$park_id)->get(['account','banned_withdraw','account_name']);
        if(!empty($account_manages)){
            if(!empty($account_manages[0]['banned_withdraw'])){
                return $this->responseFailed('该账号已被冻结，无法提现',1);
            }
            $account=$account_manages[0]['account'];
            $account_name = $account_manages[0]['bank_name'];

            $result=DB::transaction(function () use ($request,$apt_money,$account,$account_name,$park_id,$user_id) {

                // 插入提现记录
                $withdrawal = Withdrawal::create([
                    'withdrawal_no'=>get_order_no(),
                    'person_type'=>1,
                    'apply_time'=>now(),
                    'apply_money'=>$apt_money,
                    'status'=>1,
                    'account'=>$account,
                    'account_name'=>$account_name,
                    'park_id'=>$park_id,
                    'user_type'=>Property::class,
                    'user_id'=>$user_id,
                ]);

                // 车场钱包余额扣除相应的提现金额
                $park_wallet = ParkWallet::query()->where('park_id',$park_id)->first();

                $park_wallet->amount -= $apt_money;

                $park_wallet->withdrawal += $apt_money;

                $park_wallet->save();

                //  添加流水记录
                $record = $park_wallet->records()->newModelInstance([
                    'park_id' => $park_wallet->park_id,
                    'amount' => $apt_money,
                    'type' => ParkWalletBalance::WITHDRAWAL_TYPE,
                    'trade_type' => 2, // 支出
                    'balance' => $park_wallet->amount,
                ]);

                $record->order()->associate($withdrawal);

                $record->save();

                return true;
            });
            if($result){
                return $this->responseSuccess('申请成功');
            }
        }
    }
}
