<?php

namespace App\Models\Dmanger;

use App\Events\SubscribeCarportReverse;
use App\Jobs\CloseSubscribeOrder;
use App\Jobs\SubscribeCancelResponse;
use App\Models\Bills\DivideInterface;
use App\Models\Bills\OrderAmountDivide;
use App\Models\Financial\BookingFee;
use App\Models\Financial\ParkingFee;
use App\Models\Parks\Park;
use App\Models\Parks\ParkRate;
use App\Models\Parks\ParkSpace;
use App\Models\Parks\ParkSpaceLock;
use App\Models\Payment;
use App\Models\Traits\HasOrderAmountDivide;
use App\Models\User;
use App\Models\Users\ParkingSpaceRentalRecord;
use App\Models\Users\UserCar;
use App\Models\Users\UserOrder;
use App\Packages\Payments\PaymentType;
use App\Services\PaymentService;
use Illuminate\Database\Eloquent\Builder;
use App\Models\EloquentModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

/**
 * Class CarApt
 * @package App\Models\Dmanger
 *
 * @property int $car_rent_id
 */
class CarApt extends EloquentModel implements DivideInterface
{
    use SoftDeletes, HasOrderAmountDivide;

    public static $logName = "carApt";

    protected $fillable = [
        'user_id', 'park_id', 'park_space_id', 'user_car_id',
        'amount', 'total_amount', 'deduct_amount', 'refund_total_amount',
        'car_rent_id', 'car_stop_id', 'rate_cache',
        'apt_start_time', 'apt_end_time', 'apt_time', 'renewed_at'
    ];

    protected $casts = [
        'rate_cache' => 'array'
    ];

    protected $dates = [
        'apt_start_time', 'apt_end_time', 'renewed_at'
    ];

    const RATE_CACHE_FIELDS = [
        'id', 'no', 'name', 'is_workday', 'start_period', 'end_period', 'down_payments', 'down_payments_time',
        'time_unit', 'payments_per_unit', 'first_day_limit_payments', 'is_active', 'park_area_id', 'type'
    ];

    protected $couponTypes = [
        1 => 'App\Models\Admin',
        2 => 'App\Models\Park\Parks'
    ];

    public static $publishType = [
        1 => 'App\Models\Admin',
        3 => 'App\Models\Property'
    ];

    public function setRateCacheAttribute($value)
    {
        $this->attributes['rate_cache'] = $this->asJson(
            array_merge($this->fromJson($this->attributes['rate_cache'] ?? '{}'), $value)
        );
    }

    // 关联停车场、停车记录、订单模型
    public function parks()
    {
        return $this->belongsTo(Park::class, 'park_id', 'id');
    }

    public function carRent()
    {
        return $this->belongsTo(CarRent::class);
    }

    public function rentals()
    {
        return $this->hasManyThrough(ParkingSpaceRentalRecord::class, CarRent::class, 'id', 'car_rent_id', 'car_rent_id');
    }

    public function owner()
    {
        return $this->hasOneThrough(User::class, CarRent::class, 'id', 'id', 'car_rent_id', 'user_id')->where('car_rents.user_type', User::class);
    }

//    public function carStop()
//    {
//        return $this->belongsTo(CarStop::class);
//    }

    public function car()
    {
        return $this->belongsTo(UserCar::class,'user_car_id');
    }

    public function userOrder()
    {
        return $this->belongsTo(UserOrder::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);

    }

    public function userCar()
    {
        return $this->belongsTo(UserCar::class,'user_car_id');
    }

    public function orders()
    {
        return $this->aptOrder()->whereNotNull('paid_at');
    }

    public function aptOrder()
    {
        return $this->hasMany(CarAptOrder::class);
    }

    public function addOrder($amount, $apt_time = 30, $is_renewal = false)
    {
        return \DB::transaction(function () use ($amount, $apt_time, $is_renewal) {
            /** @var CarAptOrder $order */
            $order = $this->aptOrder()->create([
                'user_id' => $this->user_id,
                'no' => get_order_no(),
                'amount' => $amount,
                'subscribe_time' => $apt_time, // 付款时长
                'is_renewal' => $is_renewal, // 是否续约
                'user_order_id' => $this->user_order_id,
                'expired_at' => now()->addMinutes(10)
            ]);

            if ($is_renewal) {
                $this->renewed_at = now();
                $this->save();
            }

            $price = decimal_number($amount/100);

            $type = $order->type();

            $payment = new Payment([
                'user_id' => $this->user_id,
                'no' => $order->no,
                'amount' => $amount,
                'expired_at' => $order->expired_at,
                'type' => $type,
                'description' => (!$is_renewal ? "预约单" : "续约{$apt_time}分钟") .'- '.$price.'元',
                'context' => [
                    'title' => $is_renewal ? '预约延长单' : '预约单',
                    'body' => (!$is_renewal ? "预约单" : "续约{$apt_time}分钟") .'- '.$price.'元',
                    'price' => $price,
                    'type' => $type
                ]
            ]);

            $payment->payable()->associate($order);
            $payment->save();

            dispatch(new CloseSubscribeOrder($order));

            return $order;
        });
    }

    // 车位
    public function parkSpace()
    {
        return $this->belongsTo(ParkSpace::class,'park_space_id');

    }

    /**
     * 车位地锁
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOneThrough
     */
    public function lock()
    {
        return $this->hasOneThrough(ParkSpaceLock::class, ParkSpace::class, 'id',  'park_space_id', 'park_space_id');
    }

    // 预约搜索查询条件
    public function scopeSearch(Builder $query, Request $request)
    {
        // 判断是否有park_id
        if ($park_name = $request->input('park_name')) {
            $query->whereHas('parks',function ($query) use ($park_name) {
                $query->where('project_name','like',"%$park_name%");
            });
        }
        // 判断是否有时间段
        if ($aptStartTime = $request->input('apt_start_time')) {
            $query->where('apt_start_time', '>=', $aptStartTime);
        }
        if ($aptEndTime = $request->input('apt_end_time')) {
            $query->where('apt_start_time', '<=', $aptEndTime);
        }

        // 判断停车类型
        if ($carStopType = $request->input('car_stop_type')) {
            $query->whereHas('carRent', function($query) use ($carStopType) {
                $query->where('rent_type_id', $carStopType);
            });
        }

        // 车位发布类型
//        if ($car_stop_type = $request->input('car_stop_type')) {
//            // 查询业主发布车位
//            if($car_stop_type == 2){
//                $query->where('car_rent_id','!=','');
//            }else{
//                // 查询物业和云端发布车位
//                $query->whereHas('parkSpace', function ($query) use ($car_stop_type){
//                    $query->whereHas('spaceType', function ($query) use ($car_stop_type){
//                        $query->where('publisher_type',self::$publishType[$car_stop_type]);
//                    });
//                });
//            }
//        }

        // 订单号
        if ($orderNo = $request->input('order_no')) {
            $query->whereHas('userOrder',function ($query) use ($orderNo) {
                $query->where('order_no', 'like', "%{$orderNo}%");
            });
        }

        // 车牌号
        if ($carNum = $request->input('car_num')) {
            $query->whereHas('userCar',function ($query) use ($carNum) {
                $query->where('car_number','like',"%$carNum%");
            });
        }

        //判断优免类型
        if ($coupon_type= $request->input('coupon_type')) {
            $query->whereHas('orders',function ($query) use ($coupon_type) {
                $query->whereHas('coupon',function ($query) use ($coupon_type) {
                    $query->where('publisher_type', $this->couponTypes[$coupon_type]);
                });
            });
        }
        return $query;
    }

    // 出租车位的查询
    public function scopeRentSearch(Builder $query, Request $request)
    {
        if ($park_name = $request->input('park_name')) {
            $query->whereHas('parks',function ($query) use ($park_name) {
                $query->where('project_name', 'like',"%$park_name%");
            });
        }

        if ($rent_no = $request->input('rent_no')) {
            $query->whereHas('userOrder',function ($query) use ($rent_no) {
                $query->where('order_no', 'like',"%$rent_no%");
            });
        }

        if ($car_num = $request->input('car_num')) {
            $query->whereHas('userCar',function ($query) use ($car_num) {
                $query->where('car_number', $car_num);
            });
        }

        if ($rent_start_time = $request->input('rent_start_time')) {
            $query->whereHas('carRent', function ($query) use ($rent_start_time) {
                $query->where('rent_start_time', '>=', $rent_start_time);
            });
        }


//        $query->whereHas('carRent',function($query) use ($request) {
//            if ($rent_end_time= $request->input('rent_end_time')) {
//                $car_rent_id = CarRent::where('rent_end_time','<=',$rent_end_time)->pluck('id');
//                $query->whereIn('car_rent_id',$car_rent_id);
//            }
//        });
    }

    public function cacheRate(Model $rate)
    {
        $columns = ['id', 'down_payments', 'down_payments_time', 'payments_per_unit', 'time_unit'];

        if ($rate instanceof CarRent) {
            $cache = [
                'id' => $rate->getKey(),
                'down_payments' => 0,
                'down_payments_time' => 0,
                'payments_per_unit' => $rate->rent_price,
                'time_unit' => 60
            ];
        } else {
            $cache = $rate->only($columns);
        }

        $this->rate_cache = $cache;

        return $this;
    }

    public function getParkRateAmount()
    {
        return $this->getCacheField('payments_per_unit');
    }

    public function getParkRateUnit()
    {
        return $this->getCacheField('time_unit');
    }

    public function getCacheField($key)
    {
        return data_get($this->rate_cache, $key);
    }

    public function totalAmount()
    {
        return $this->total_amount;
    }

    public function trueAmount()
    {
        return $this->deduct_amount;
    }

    public function getRates()
    {
        $type = $this->car_rent_id ? 1 : 2;

        $fees = BookingFee::getFees($this->park_id);

        return collect($fees->apt)->filter(function ($apt) use ($type) {
            return $apt['type'] == $type;
        })->first();
    }

    public function getSubscribeRefundAmount()
    {
        $refund_amount = $this->total_amount; // 总支付金额

        // 停车场 免费时间
        // $free_time = $this->parks->stall->free_time ?? 0;

        // 得到使用时长
        $use_time = now()->diffInMinutes($this->apt_start_time);

        // 在免费时间内取消，全额退款，否则退部分
        // if ($free_time > 0 && $use_time && $free_time > $use_time) {
        //     // 全额退款
        //     return max(0, $refund_amount);
        // }


        // 计算需要退款金额：剩余时长*单价
        // 首付时长及费用
        $down_pay_amount = $this->getCacheField('down_payments');
        $down_pay_time = $this->getCacheField('down_payments_time');

        // 减首付时长
        $use_time -= $down_pay_time;

        $refund_amount -= $down_pay_amount;

        // 获取剩余退款金额
        if ($use_time > 0 && $refund_amount > 0) {
            $amount_unit = $this->getParkRateAmount();
            $time_unit = $this->getParkRateUnit();

            // 1、按分钟计算
            // $refund_amount -= intval($time_unit / $amount_unit * $use_time);
            // 2、分段收费
            while ($use_time > 0) {
                $use_time -= $time_unit;

                $refund_amount -= $amount_unit;

                if ($refund_amount <= 0) {
                    $refund_amount = 0;
                    break;
                }
            }
        }

        return max(0, $refund_amount);
    }

    public function cancel()
    {
        \DB::transaction(function () {
            $this->finish();

            // 车位恢复发布状态
            // $this->parkSpace()->update([
            //     'status' => 1
            // ]);

            // dispatch(new SubscribeCancelResponse($this));
            event(new SubscribeCarportReverse($this));
        });
    }

    /**
     * finish
     *
     * @param \DateTimeInterface|null $enter_time 用户入场时间
     */
    public function finish(\DateTimeInterface $enter_time = null)
    {
        $apt_end_time = $enter_time ?? now();
        // 更新截止时间
        $this->apt_end_time = $apt_end_time;
        $this->apt_time = $apt_end_time->diffInMinutes($this->apt_start_time);

        // 退款
        $this->refundAmount();

        $this->save();

        // 预约费分账
        $this->addDivideRecord();
    }

    public function updateTrueAmount($amount = 0)
    {
        $this->deduct_amount = $this->total_amount - $amount; // 实际扣款

        return $this;
    }

    public function refundAmount()
    {
        $amount = $this->getSubscribeRefundAmount();

        $this->updateTrueAmount($amount);

        $this->refund_total_amount = $amount; // 实际退款

        $this->orders()
            ->whereNull('refunded_at')
            ->where('status', CarAptOrder::STATUS_PAID)
            ->latest()
            ->each(function (CarAptOrder $order) use (&$amount) {
                if ($amount < $order->amount()) {
                    $refund_amount = $amount;

                    $amount = 0;
                } else {
                    $amount -= $order->amount();

                    $refund_amount = $order->amount();
                }

                $refund_no = get_order_no();

                $order->initRefund($refund_no, $refund_amount)->cancel();

                if ($refund_amount > 0) {
                    app(PaymentService::class)->refund($order, $refund_amount, $refund_no, PaymentType::PAYMENT_TYPE_SUBSCRIBE_REFUND);
                }
        });

        return $this;
    }

    public function getHasRenewalAttribute()
    {
        return $this->hasRenewal();
    }

    public function hasRenewal()
    {
        return !is_null($this->renewed_at);
    }

    /**
     * 发放预约分成费用
     *
     * @param $divide
     */
    public function sendOwnerAmount($divide)
    {
        $amount = $divide->owner_fee;

        // 更新出租记录 预约费
        $this->updateRental($amount);

        if ($amount <= 0) return;

        $owner = $this->owner ? $this->owner : null;

        if ($owner instanceof User) {
            \DB::transaction(function () use ($owner, $amount) {
                tap($owner->rentalIncomeBills()->firstOrNew([
                    'order_type' => $this->getMorphClass(),
                    'order_id' => $this->getKey()
                ]), function ($bill) use ($owner, $amount) {
                    $owner->incrementRentalAmount($amount);

                    $bill->park_id = $this->park_id;
                    $bill->order()->associate($this);

                    $bill->no = get_order_no();

                    $bill->amount = $amount;
                    $bill->rental_amount = $owner->rental_amount;

                    $bill->type = 0; // 0增加 1 减少

                    $bill->body = '预约费分成';

                    $bill->save();
                });
            });

        }
    }


    /**
     * 更新出租记录
     *
     * 刷新实际预约金额、实际预约结束时间
     *
     * @param $amount
     */
    public function updateRental($amount)
    {
        $this->loadMissing('carRent');

        if (!$this->carRent) {
            return;
        }

        $rental = $this->rentals()
            ->where('car_apt_id', $this->getKey())
            ->where('status', ParkingSpaceRentalRecord::STATUS_PENDING)
            ->whereNull('finished_at')
            ->first();

        if (!$rental) {
            return;
        }

        // 更新 预约结束时间，预约金额
        $rental->fill([
            'subscribe_end_time' => now(),
            'subscribe_amount' => $amount, // 实际预约结算金额
        ]);

        // 实际结算金额，预约费+停车费
        $rental->amount = $rental->subscribe_amount + $rental->stop_amount;

        $rental->save();
    }
}
