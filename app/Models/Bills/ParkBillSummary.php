<?php

namespace App\Models\Bills;

use App\Models\EloquentModel;
use App\Models\Parks\Park;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class ParkBillSummary extends EloquentModel
{
    use SoftDeletes;

    const BILL_TYPE_PARKING_RESERVE = 'parking_reserve'; // 停车场预约费
    const BILL_TYPE_TOTAL = 'total'; // 汇总

    const BILL_TYPE_OWNER_RESERVE = 'owner_reserve'; // 业主预约费

    public static $billTypeMaps = [
        self::BILL_TYPE_OWNER_RESERVE => '业主预约费',
        self::BILL_TYPE_PARKING_RESERVE => '停车场预约费',
        self::BILL_TYPE_TOTAL => '汇总'
    ];

    protected $fillable = [
        'park_id', 'date', 'amount', 'type', 'bill_type', 'income', 'expenses'
    ];

    public function park()
    {
        return $this->belongsTo(Park::class);
    }

    public function getBillTypeNameAttribute()
    {
        return array_get(ParkBillSummary::$billTypeMaps, $this->bill_type, '未知业务');
    }

    public function scopeSearch(Builder $query, Request $request)
    {
        if ($type = $request->input('state')) {
            $query->where('type', $type);
        }

        if ($bill_type = $request->input('order_type')) {
            $query->where('bill_type', $bill_type);
        }

        if ($park_name = $request->input('park_name')) {
            $query->whereHas('park', function ($query) use ($park_name) {
                $query->where('project_name', 'like', "%$park_name%");
            });
        }

        $start = $request->input('start_time');
        $end = $request->input('end_time');
        if ($start && $end) {
            // 日账单
            if ($type == 'day') {
                $query->whereDate('date', '>=', $start);
                $query->whereDate('date', '<=', $end);
            }
            // 月账单
            if ($type == 'month') {
                $query->whereDate('date', '>=', date('Y-m', strtotime($start)));
                $query->whereDate('date', '<=', date('Y-m', strtotime($end)));
            }
        }

        return $query;
    }
}
