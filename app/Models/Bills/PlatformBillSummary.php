<?php

namespace App\Models\Bills;

use App\Models\EloquentModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class PlatformBillSummary extends EloquentModel
{
    use SoftDeletes;

    protected $fillable = [
        'date', 'amount', 'type', 'bill_type', 'income', 'expenses'
    ];

    protected $appends = [
        'bill_type_name'
    ];

    public function getBillTypeNameAttribute()
    {
        return array_get(ParkBillSummary::$billTypeMaps, $this->bill_type, '未知业务');
    }

    public function scopeSearch(Builder $query, Request $request)
    {
        if ($type = $request->input('state')) {
            $query->where('type', $type);
        }

        if ($bill_type = $request->input('business')) {
            $query->where('bill_type', $bill_type);
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
