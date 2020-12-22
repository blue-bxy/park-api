<?php
namespace App\Services;

use App\Models\Bills\OrderAmountDivide;
use App\Models\Bills\ParkBillSummary;
use App\Models\Bills\ParkWalletBalance;
use App\Models\Bills\PlatformBillSummary;
use App\Models\Dmanger\CarApt;
use App\Models\Users\ParkingSpaceRentalBill;
use App\Models\Withdrawal\AptOrderDay;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class SummaryOrderService{

    /**
     * 统计前一天的车场预约单的正常结算收入和退款
     * @return mixed
     * @throws \Throwable
     */
    public function summary(){
        $time=date("Y-m-d",strtotime("-1 day"));
        $start_time=Carbon::parse($time)->startOfDay();
        $end_time=Carbon::parse($time)->endOfDay();
        $query=OrderAmountDivide::query();
        $query->whereBetween('created_at',[$start_time,$end_time]);
        $query->where("model_type",CarApt::class);
        $query->selectRaw('park_id,sum(park_fee) as amount');
        $query->groupBy('park_id');
        $data=$query->get();

        //退款
        $refund_query=CarApt::query();
        $refund_query->whereBetween('apt_end_time',[$start_time,$end_time]);
        $refund_query->selectRaw('park_id,sum(refund_total_amount) as refund_amount');
        $refund_query->groupBy('park_id');
        $refund_data=$refund_query->get();

        if(!empty($data)){
            $result=DB::transaction(function () use ($data,$refund_data,$time){
                foreach ($data as $value){
                    $res=AptOrderDay::create([
                        'park_id'=>$value['park_id'],
                        'no'=>get_order_no(),
                        'type'=>1,//正常结算收入
                        'amount'=>$value["amount"],
                        'time'=>$time,
                    ]);
                }
                foreach ($refund_data as $value){
                    if(!empty($value['refund_amount'])){
                        $res=AptOrderDay::create([
                            'park_id'=>$value['park_id'],
                            'no'=>get_order_no(),
                            'type'=>3,//退款
                            'amount'=>$value['refund_amount'],
                            'time'=>$time,
                        ]);
                    }
                }
                return $res;
            });
            return $result;
        }else{
            return false;
        }
    }

    public function day($time = null)
    {
        if (is_null($time)) {
            $time = Carbon::yesterday();
        }

        if ($time instanceof \DateTimeInterface) {
            $time = $time->format('Y-m-d');
        }

        $query = ParkWalletBalance::query();

        $query->whereDate('created_at', $time);

        /** @var Collection $items */
        $items = $query->get();

        foreach ($items->groupBy('park_id') as $park_id => $item) {
            // 汇总
            $income = $item->where('trade_type', 1)->sum('amount');
            $expenses = $item->where('trade_type', 2)->sum('amount');
            $this->total($park_id, $income, $income, $expenses, $time);

            // 预约费
            $income = $item->where('trade_type', 1)->where('type', 'reserve')->sum('amount');
            $expenses = $item->where('trade_type', 2)->where('type', 'reserve')->sum('amount');
            $this->reserve($park_id, $income, $income, $expenses, $time);

            // 停车费

            // 线下停车费
        }


    }

    public function month($time = null)
    {
        if (is_null($time)) {
            $time = now()->subMonths();
        }

        if ($time instanceof \DateTimeInterface) {
            $time = $time->format('Y-m');
        }

        $query = ParkBillSummary::query();

        $query->whereRaw("date_format(date, '%Y-%m') = '{$time}'");

        $query->where('type', 'day');

        $items = $query->get();

        foreach ($items->groupBy('park_id') as $park_id => $item) {
            // 汇总
            $income = $item->sum('income');
            $expenses = $item->sum('expenses');
            $amount = $item->sum('amount');
            $this->total($park_id, $income, $amount, $expenses, $time, 'month');

            // 预约费
            $park_fill = $item->where('bill_type', ParkBillSummary::BILL_TYPE_PARKING_RESERVE);
            $income = $park_fill->sum('income');
            $expenses = $park_fill->sum('expenses');
            $amount = $park_fill->sum('amount');

            $this->reserve($park_id, $income, $amount, $expenses, $time, 'month');

            $owner_fill = $item->where('bill_type', ParkBillSummary::BILL_TYPE_OWNER_RESERVE);
            $income = $owner_fill->sum('income');
            $expenses = $owner_fill->sum('expenses');
            $amount = $owner_fill->sum('amount');

            $this->reserve($park_id, $income, $amount, $expenses, $time, 'month', ParkBillSummary::BILL_TYPE_OWNER_RESERVE);
        }

        // 平台
        $query = ParkBillSummary::query();

        $query->where('date', $time);
        $query->where('type', 'month');

        $query->select('date', 'type', 'bill_type');
        $query->selectRaw('sum(amount) as amount');
        $query->selectRaw('sum(income) as income');
        $query->selectRaw('sum(expenses) as expenses');
        $query->groupBy('type', 'bill_type');

        $items = $query->get();

        foreach ($items as $item) {
            PlatformBillSummary::query()->create($item->toArray());
        }
    }

    public function owner($time = null)
    {
        $query = ParkingSpaceRentalBill::query();

        if (is_null($time)) {
            $time = Carbon::yesterday();
        }

        if ($time instanceof \DateTimeInterface) {
            $time = $time->format('Y-m-d');
        }

        $query->whereDate('created_at', $time);

        $query->whereNotNull('park_id');

        $query->with('order');

        $items = $query->get();

        foreach ($items->groupBy('park_id') as $park_id => $item) {
            // 预约费
            $income = $item->where('type', 0)->sum('amount');
            $expenses = $item->where('type', 1)->sum('amount');
            $this->reserve($park_id, $income, $expenses, $time, 'day', ParkBillSummary::BILL_TYPE_OWNER_RESERVE);
        }
    }

    protected function total($park_id, $income, $amount, $expenses, $date, $type = 'day')
    {
        $bill = new ParkBillSummary([
            'park_id' => $park_id,
            'income' => $income,
            'expenses' => $expenses,
            'amount' => $amount,
            'date' => $date,
            'type' => $type,
            'bill_type' => ParkBillSummary::BILL_TYPE_TOTAL
        ]);

        $bill->save();
    }

    protected function reserve($park_id, $income, $amount, $expenses, $date, $type = 'day', $bill_type = ParkBillSummary::BILL_TYPE_PARKING_RESERVE)
    {
        $bill = new ParkBillSummary([
            'park_id' => $park_id,
            'income' => $income,
            'expenses' => $expenses,
            'amount' => $amount,
            'date' => $date,
            'type' => $type,
            'bill_type' => $bill_type
        ]);

        $bill->save();
    }

    public function platform($time = null)
    {
        if (is_null($time)) {
            $time = Carbon::yesterday();
        }

        if ($time instanceof \DateTimeInterface) {
            $time = $time->format('Y-m-d');
        }

        $query = ParkBillSummary::query();

        $query->whereDate('date', $time);

        $query->select('date', 'type', 'bill_type');
        $query->selectRaw('sum(amount) as amount');
        $query->selectRaw('sum(income) as income');
        $query->selectRaw('sum(expenses) as expenses');
        $query->groupBy('type', 'bill_type');

        $items = $query->get();

        foreach ($items as $item) {
            PlatformBillSummary::query()->create($item->toArray());
        }
    }
}
