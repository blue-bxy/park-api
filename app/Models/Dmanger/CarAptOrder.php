<?php

namespace App\Models\Dmanger;

use App\Events\SubscribeCarport;
use App\Models\EloquentModel;
use App\Models\Parks\ParkSpace;
use App\Models\Payment;
use App\Models\PaymentInterface;
use App\Models\User;
use App\Models\Coupons\Coupon;
use App\Models\Users\UserOrder;
use App\Packages\Payments\PaymentType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * Class CarAptOrder
 * @package App\Models\Dmanger
 *
 * @property string $no
 * @property int $amount
 * @property boolean $is_renewal
 * @property-read boolean $has_paid
 * @property Payment $payable
 * @property CarApt $carApt
 * @property UserOrder $order
 * @property User $user
 * @property Carbon $expired_at
 */
class CarAptOrder extends EloquentModel implements PaymentInterface
{
    use SoftDeletes;

    public static $logName = "carAptOrder";

    const STATUS_PENDING = 'pending';
    const STATUS_PAID = 'paid';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_FAILED = 'failed';
    const STATUS_REFUNDED = 'refunded';

    const STATUSES = [
        self::STATUS_PENDING => '待支付',
        self::STATUS_PAID => '已支付',
        self::STATUS_CANCELLED => '已取消',
        self::STATUS_FAILED => '已失败',
        self::STATUS_REFUNDED => '已退款'
    ];

    protected $fillable = [
        'user_id', 'car_apt_id','transaction_id', 'currency',
        'status','no','refund_no', 'coupon_id', 'is_renewal',
        'refund_id', 'refund_no', 'user_order_id',
        'expired_at',
        'paid_at','cancelled_at','refunded_at','finished_at','failed_at', 'expired_at',
        'amount', 'subscribe_time',
        'payment_gateway'
    ];

    protected $dates = [
        'paid_at','cancelled_at','refunded_at','finished_at','failed_at', 'expired_at',
    ];

    protected $casts = [
        'is_renewal' => 'boolean'
    ];

    public function getStatusRenameAttribute()
    {
        return self::STATUSES[$this->status];
    }

    public function getHasPaidAttribute()
    {
        return !is_null($this->paid_at);
    }

    public function carApt()
    {
        return $this->belongsTo(CarApt::class,'car_apt_id','id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(UserOrder::class, 'user_order_id');
    }

    public function payable()
    {
        return $this->morphOne(Payment::class, 'payable');
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function space()
    {
        return $this->hasOneThrough(ParkSpace::class, CarApt::class, 'id', 'id', 'car_apt_id', 'park_space_id');
    }

    public function scopeSearch(Builder $query, Request $request)
    {
        // 订单号
        if ($no = $request->input('order_no')) {
            $query->where('no','like',"%{$no}%");
        }
        if($start_time=$request->input('start_time')){
            $query->where('finished_at','>=',$start_time);
        }
        if($end_time=$request->input('end_time')){
            $query->where('finished_at','<=',$end_time);
        }
        if($car_num=$request->input('car_num')){
            $query->whereHas('carApt',function ($query) use($car_num){
                $query->whereHas('userCar',function ($query) use ($car_num){
                    $query->where('car_number','like',"%$car_num%");
                });
            });
        }
        if($status=$request->input('status')){
            $query->where('status',$status);
        }

        if($park_name = $request->input('park_name')){
            $query->whereHas('carApt',function ($query) use ($park_name){
                $query->whereHas('parks',function ($query) use ($park_name){
                    $query->where('project_name','like',"%$park_name%");
                });
            });
        }

        return $query;
    }

    /**
     * paid
     *
     * @param array $attributes
     * @throws \Throwable
     */
    public function paid(array $attributes)
    {
        $this->amount = $attributes['total_amount'];
        $this->paid_at = now();
        $this->status = self::STATUS_PAID;
        $this->payment_gateway = $attributes['gateway'];
        $this->transaction_id = $attributes['transaction_id'] ?? Str::orderedUuid();
        $this->save();

        // 锁定车位
        $this->space()->update([
            'status' => 4 // 已预约
        ]);

        // 针对续约订单，在原预约单累计付款金额
        if ($this->is_renewal) {
            $this->carApt->amount += $attributes['total_amount'];
            $this->carApt->total_amount += $attributes['total_amount'];

            // 延长相应时间
            $end_time = $this->carApt->apt_end_time;
            $this->carApt->apt_end_time = $end_time->addMinutes($this->subscribe_time);
            $this->carApt->apt_time += $this->subscribe_time;

            $this->carApt->save();
        }

        $this->order->paid($attributes);

        // 支出记录
        $this->user->expenditure($this, $attributes['total_amount']);

        event(new SubscribeCarport($this));
    }

    /**
     * 取消订单
     */
    public function cancel()
    {
        if (!$this->has_paid || !is_null($this->cancelled_at)) {
            return;
        }

        \DB::transaction(function () {
            $this->status = self::STATUS_CANCELLED;
            $this->cancelled_at = now();

            $this->save();

            $this->payable()->cancel();
        });
    }

    public function initRefund($refund_no, $refund_amount)
    {
        $this->status = CarAptOrder::STATUS_REFUNDED;
        $this->refund_no = $refund_no;
        $this->refund_amount = $refund_amount;
        $this->refunded_at = now();

        return $this;
    }

    public function gateway()
    {
        return $this->payment_gateway;
    }

    public function amount()
    {
        return $this->amount;
    }

    public function type()
    {
        return $this->is_renewal
            ? PaymentType::PAYMENT_TYPE_SUBSCRIBE_RENEWAL_ORDER
            : PaymentType::PAYMENT_TYPE_SUBSCRIBE_ORDER;
    }

    public function tradeNo()
    {
        return $this->transaction_id;
    }

    public function orderNo()
    {
        return $this->no;
    }
}
