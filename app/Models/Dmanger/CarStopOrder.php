<?php

namespace App\Models\Dmanger;

use App\Models\EloquentModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CarStopOrder extends EloquentModel
{
        use SoftDeletes;

        public static $logName = "carStopOrder";

        protected $fillable = [
            'user_id', 'car_stop_id','transaction_id', 'currency',
            'status','no','refund_no', 'coupon_id', 'is_renewal',
            'refund_id', 'refund_no',
            'expired_at',
            'paid_at','cancelled_at','refunded_at','finished_at','failed_at', 'expired_at',
            'amount', 'stop_time',
            'payment_gateway'
        ];
}
