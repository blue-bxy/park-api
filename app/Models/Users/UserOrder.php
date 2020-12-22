<?php

namespace App\Models\Users;

use App\Events\Orders\Finish;
use App\Models\Bills\OrderAmountDivide;
use App\Models\Coupons\Coupon;
use App\Models\Dmanger\CarAptOrder;
use App\Models\Dmanger\CarRent;
use App\Models\EloquentModel;
use App\Models\Parks\Park;
use App\Models\Parks\ParkArea;
use App\Models\Parks\ParkRate;
use App\Models\Parks\ParkSpace;
use App\Models\Traits\HasMap;
use App\Models\User;
use App\Packages\Payments\Gateway;
use App\Services\ParkRateService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Dmanger\CarApt;
use App\Models\Dmanger\CarStop;

/**
 * Class UserOrder
 * @package App\Models\Users
 * @property User $user
 * @property UserRefund $refund
 */
class UserOrder extends EloquentModel
{
    use SoftDeletes, HasMap;

    public static $logName = "userOrder";

    const ORDER_STATE_PENDING   = 'pending';
    const ORDER_STATE_PAID      = 'paid';
    const ORDER_STATE_REFUNDED  = 'refunded'; //已退款、
    const ORDER_STATE_CANCELLED = 'cancelled'; //已取消
    const ORDER_STATE_FINISHED  = 'finished'; //已完成
    const ORDER_STATE_FAILED    = 'failed';//已失败
    const ORDER_STATE_COMMENTED = 'commented';//已评价

    public static $orderStateMaps = [
        self::ORDER_STATE_PENDING   => '待付款',
        self::ORDER_STATE_PAID      => '进行中', // 未进场、已进场
        self::ORDER_STATE_REFUNDED  => '已退款',
        self::ORDER_STATE_CANCELLED => '已取消',
        self::ORDER_STATE_FINISHED  => '已完成', // 离场
        self::ORDER_STATE_FAILED    => '已失败',
        self::ORDER_STATE_COMMENTED => '已评价',
    ];

    const ORDER_PAID_STATE_PENDING = 'pending';
    const ORDER_PAID_STATE_PAID = 'paid';
    const ORDER_PAID_STATE_REFUNDING = 'refunding';
    const ORDER_PAID_STATE_REFUNDED = 'refunded';
    const ORDER_PAID_STATE_FAILED = 'failed';
    const ORDER_PAID_STATE_CANCELLED = 'cancelled';

    public static $orderPaidStateMaps = [
        self::ORDER_PAID_STATE_PENDING => '待付款',
        self::ORDER_PAID_STATE_PAID => '已付款',
        self::ORDER_PAID_STATE_REFUNDING => '退款中',
        self::ORDER_PAID_STATE_REFUNDED => '已退款',
        self::ORDER_PAID_STATE_CANCELLED => '已取消', // 取消与失败区别：取消是主动、失败是自动
        self::ORDER_PAID_STATE_FAILED => '已失败',
    ];

    const ORDER_FAIL_NO_PAID = 'no_paid';
    const ORDER_FAIL_CANCEL = 'cancel';

    public static $orderFailReasons = [
        self::ORDER_FAIL_CANCEL => '预约取消',
        self::ORDER_FAIL_NO_PAID => '超时未付款'
    ];


    public static $orderTypeMap = [
        1 => '预约',
        2 => '停车',
    ];

    protected $couponTypes = [
        1 => 'App\Models\Admin',
        2 => 'App\Models\Park\Parks'
    ];

    public static $publishType = [
        1 => 'App\Models\Admin',
        3 => 'App\Models\Property'
    ];

	protected $fillable = [
        'order_no', 'user_id', 'park_id', 'coupon_id', 'car_stop_id', 'user_car_id', 'car_apt_id',
        'subscribe_amount', 'amount', 'discount_amount', 'refund_amount', 'total_amount',
		'payment_no', 'payment_gateway', 'status',
        'body', 'fail_status','car_rent_id',
        'paid_at', 'cancelled_at', 'refunded_at', 'finished_at', 'failed_at', 'commented_at',
        'car_in_time', 'car_out_time', 'car_stop_time', 'expired_at', 'cancel_renewal_notice', 'renewal_notice'
    ];

	protected $dates = [
        'paid_at', 'cancelled_at', 'refunded_at', 'finished_at', 'failed_at', 'commented_at',
        'car_in_time', 'car_out_time', 'car_stop_time', 'expired_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function parks()
    {
        return $this->belongsTo(Park::class,'park_id','id');
    }
    public function parkArea()
    {
        return $this->belongsTo(ParkArea::class,'park_id','park_id');
    }

    public function car()
    {
        return $this->belongsTo(UserCar::class, 'user_car_id')->withTrashed();
    }

    public function carRent(){
        return $this->belongsTo(CarRent::class,'car_rent_id');
    }
    public function coupon()
    {
        return $this->belongsTo(UserCoupon::class,'coupon_id');
    }
    public function apt()
    {
        return $this->carApts()->whereNotNull('car_rent_id');
    }

    /**
     * 获取预约记录
     *
     * @return HasOne|CarApt|object|null
     */
    public function carApts()
    {
        return $this->hasOne(CarApt::class)->withTrashed();
    }

    public function subscribeSpace()
    {
        return $this->hasOneThrough(ParkSpace::class, CarApt::class,
            'user_order_id',
            'id',
            'id',
            'park_space_id'
        )->withoutGlobalScopes();
    }

    /**
     * 获取停车记录
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function carStop()
    {
        return $this->belongsTo(CarStop::class, 'car_stop_id', 'id')->withTrashed();
    }

    /**
     * stop
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function stop()
    {
        return $this->hasOne(CarStop::class, 'user_order_id');
    }

    // 停车分成
    public function divide()
    {
        return $this->hasOneThrough(OrderAmountDivide::class,CarStop::class,'id','model_id','car_stop_id');
    }

    /**
     * 停车车位
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOneThrough
     */
    public function stopSpace()
    {
        return $this->hasOneThrough(ParkSpace::class, CarStop::class,
            'user_order_id', 'id', 'id', 'park_space_id'
        )->withoutGlobalScopes();
    }

    public function amount()
    {
        return $this->order_type == 1 ? $this->subscribe_amount : $this->amount;
    }

    /**
     * 获取评价信息
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function comment()
    {
        return $this->hasOne(UserComment::class, 'order_id', 'id');
    }

    /**
     * 已审核通过的评价
     *
     * @return mixed
     */
    public function reviewedComment()
    {
        return $this->comment()->reviewed();
    }

    /**
     * 获取退款记录
     */
    public function refund()
    {
        return $this->hasOne(UserRefund::class, 'order_id', 'id');
    }

    /**
     * status_rename
     *
     * @return string
     */
    public function getStatusRenameAttribute()
    {
        $rename = self::$orderStateMaps[$this->status];

        if (!$this->hasCame() && $this->status == self::ORDER_PAID_STATE_PAID) {
            $rename .= ' 未进场';
        }

        return $rename;
    }

    public function getOrderTypeRenameAttribute()
    {
        return self::$orderTypeMap[$this->order_type];
    }

    //条件查询
    public function scopeSearch(Builder $query, Request $request)
    {
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }
        if ($starttime = $request->input('starttime')) {
            $query->where('paid_at', '>=', $starttime);
        }
        if ($endtime = $request->input('endtime')) {
            $query->where('paid_at', '<=', $endtime);
        }

        if ($payment_no = $request->input('payment_no')) {
            $query->where('payment_no', 'like', "%$payment_no%");
        }

        if ($order_no = $request->input('order_no')) {
            $query->where('order_no', 'like', "%$order_no%");
        }

        if($order_type=$request->input('order_type')){
            $query->where('order_type',$order_type);
        }

        if ($park_name = $request->input('park_name')) {
            $query->whereHas('parks',function ($query) use ($park_name) {
                $query->where('project_name', 'like',"%$park_name%");
            });

        }

        // 判断是否有时间段
        if ($apt_start_time = $request->input('start_time')) {
            $query->whereHas('carApts', function ($query) use ($apt_start_time) {
                $query->where('apt_start_time', '>=', $apt_start_time);
            });
        }

        if ($apt_end_time = $request->input('end_time')) {
            $query->whereHas('carApts', function ($query) use ($apt_end_time) {
                $query->where('apt_start_time', '<=', $apt_end_time);
            });
        }

        if ($car_num = $request->input('car_num')) {
            $query->whereHas('car', function ($query) use ($car_num) {
                $query->where('car_number', 'like', "%$car_num%");
            });
        }

        if ($car_stop_type = $request->input('car_stop_type')) {
            $query->whereHas('carApts', function ($query) use ($car_stop_type) {
                $query->whereHas('carRent', function ($query) use ($car_stop_type){
                    $query->where('rent_type_id', $car_stop_type);
                });
            });
        }
        // 车位发布类型
//        if ($car_stop_type = $request->input('car_stop_type')) {
//            // 查询业主发布车位
//            if($car_stop_type == 2){
//                $query->whereHas('carApts', function ($query) use ($car_stop_type) {
//                    $query->where('car_rent_id','!=','');
//                });
//            }else{
//                // 查询物业和云端发布车位
//                $query->whereHas('carApts', function ($query) use ($car_stop_type) {
//                    $query->whereHas('parkSpace', function ($query) use ($car_stop_type){
//                        $query->whereHas('spaceType', function ($query) use ($car_stop_type){
//                            $query->where('publisher_type',self::$publishType[$car_stop_type]);
//                        });
//                    });
//                });
//            }
//        }

        //判断优免类型
        if ($coupon_type= $request->input('coupon_type')) {
            $query->whereHas('coupon',function ($query) use ($coupon_type) {
                $query->where('publisher_type', $this->couponTypes[$coupon_type]);
            });
        }

        // 出租唯一标识号
        if ($rent_no= $request->input('rent_no')) {
            $query->whereHas('carApts',function ($query) use ($rent_no) {
                $query->whereHas('carRent',function ($query) use ($rent_no) {
                    $query->where('rent_no','like',"%$rent_no%");
                });
            });
        }
        return $query;
    }

    /**
     * canComment
     *
     * @return bool
     */
    public function canComment()
    {
        return $this->status != self::ORDER_STATE_COMMENTED
            && $this->finished_at
            && !$this->commented_at;
    }

    public function hasPaid()
    {
        return $this->status !== self::ORDER_STATE_PENDING
            && !is_null($this->paid_at);
    }

    public function getPaymentGatewayNameAttribute()
    {
        return Gateway::getTypeName($this->payment_gateway);
    }

    public function getPaidStatusRenameAttribute()
    {
        return array_get(self::$orderPaidStateMaps, $this->paid_status);
    }

    public function hasFailed()
    {
        return $this->status == self::ORDER_STATE_FAILED;
    }

    public function hasRefund()
    {
        if ($this->relationLoaded('refund')) {
            return $this->refund ? true : false;
        }

        return $this->refund()->exists();
    }

    public function getFailedReason()
    {
        return $this->fail_status
            ? array_get(self::$orderFailReasons, $this->fail_status, null)
            : null;
    }

    public function paid(array $attributes)
    {
        if ($this->hasPaid()) {
            $this->total_amount += $attributes['total_amount'];
        } else {
            $this->total_amount = $attributes['total_amount'];
            $this->payment_gateway = $attributes['gateway'];
            $this->paid_at = now();
            $this->status = self::ORDER_STATE_PAID;
        }

        $this->save();
    }

    public function hasCame()
    {
        return !is_null($this->car_in_time);
    }

    public function hasOut()
    {
        return !is_null($this->car_out_time);
    }

    public function hasStop()
    {
        return !is_null($this->car_stop_time);
    }

    /**
     * 取消预约
     * @param bool $cancel
     */
    public function cancel(bool $cancel = true)
    {
        \DB::transaction(function () use ($cancel) {
            $this->status = self::ORDER_STATE_CANCELLED;
            $this->fail_status = $cancel ? self::ORDER_FAIL_CANCEL : self::ORDER_FAIL_NO_PAID;

            $this->cancelled_at = now();

            // $this->carApts()->delete();
            // $this->carStop()->delete();
            // 释放车位
            $this->releaseSpace();

            // 未发生退款或未进场可以退余款
            if (is_null($this->refunded_at) || !$this->hasCame()) {
                // 进行部分退款
                $this->refunded_at = now();

                $this->carApts->cancel();
            }

            $this->save();

            return $this;
        });
    }

    /**
     * 预约车辆入场
     *
     * @param array $attributes
     * @param array $values
     */
    public function beganEnter(array $attributes, array $values = [])
    {

        \DB::transaction(function () use ($attributes, $values) {
            $stop = $this->stop()->updateOrCreate($attributes, $values);

            // 用户入场同步
            $this->car_stop_id = $stop->getKey();
            $this->car_in_time = $stop->car_in_time;

            $this->finishSubscribe()->save();

            // 判断是否需要补位。
            if ($this->fillIn($rate = $this->getParkRateByUnexpected())) {
                app(ParkRateService::class)->fill($rate);
            }
        });
    }

    /**
     * 用户入场，完成预约
     */
    public function finishSubscribe()
    {
        if (is_null($this->refunded_at) && $this->hasCame()) {
            // 进行部分退款
            $this->refunded_at = now();

            $this->carApts->finish($this->car_in_time);
        }

        return $this;
    }

    /**
     * 订单完成
     *
     * @param \DateTimeInterface $out_time 出场时间
     * @param int $stop_time 停车时长，单位分钟
     * @param string|null $out_img 出场拍照
     * @param int $price 停车费
     * @throws \Throwable
     */
    public function finish(\DateTimeInterface $out_time, int $stop_time, string $out_img = null, $price = 0)
    {
        \DB::beginTransaction();
        try {

            $this->setFinish();

            $this->car_out_time = $out_time;

            $this->amount = $price;
            $this->save();

            // 针对已停车
            if ($this->car_stop_id) {
                $this->stop->fill([
                    'car_out_time' => $out_time,
                    'car_out_img' => $out_img,
                    'stop_time' => $stop_time,
                    'stop_price' => $price
                ])->save();
            }

            event(new Finish($this));

            \DB::commit();
        } catch (\Exception $exception) {
            \DB::rollBack();

            logger($exception);
        }
    }

    public function setFinish()
    {
        $this->status = self::ORDER_STATE_FINISHED;
        $this->finished_at = now();

        return $this;
    }

    /**
     * 释放车位
     */
    public function releaseSpace()
    {
        $space = $this->getParkSpace();

        if (!$space) return;

        \DB::beginTransaction();
        try {
            /** @var CarRent $rental */
            $rental = $space->getRate();

            $status = $rental->rent_type_id == 2
                ? $rental->rent_status : ($this->fillIn($this->getParkRateByUnexpected()) ? 0 : 1);

            $space->status = $status; // 已发布(3 to 1 or 0)
            $space->save();

            \DB::commit();
        } catch (\Exception $e) {
            logger($e);

            \DB::rollBack();
        }
    }

    public function getParkRateByUnexpected()
    {
        return ParkRate::unexpected($this->park_id);
    }

    /**
     * 是否需要补位
     *
     * @param $rate
     * @return bool
     */
    public function fillIn($rate)
    {
        if (!$rate) {
            return true;
        }

        // 发布车位被占用
        if ($rate && $rate->spaces_count >= $rate->parking_spaces_count) {
           return true;
        }

        return false;
    }

    public function subscribeRefundAmount($subscribe, $refund_amount, $reason = '预约取消', $type = 1)
    {
        $this->user->refunds()->firstOrCreate([
            'order_id' => $subscribe->getKey(),
            'order_type' => $subscribe->getMorphClass(),
            'amount' => $subscribe->total_amount,
        ], [
            'refunded_amount' => $refund_amount,
            'type' => $type, //1-普通退款 2-赔付退款
            'reason' => $reason,
            'refund_no' => get_order_no(),
        ]);
    }

    /**
     *
     * 获取停车位信息
     * 车位信息：1、预约车位、2预约与实际停车位置不一致
     *
     * @return ParkSpace|mixed|null
     */
    public function getParkSpace()
    {
        /** @var ParkSpace $subscribe_space */
        $subscribe_space = $this->subscribeSpace()->withCount('locks')->first();

        /** @var ParkSpace $stop_space */
        $stop_space = $this->stopSpace()->withCount('locks')->first();

        if ($subscribe_space && $subscribe_space->is($stop_space)) {
            // 预约与实际停车 车位相同
            return $stop_space;
        }

        return $stop_space ?? $subscribe_space;
    }

    public function scopeToday(Builder $query)
    {
        return $query->whereDate(self::CREATED_AT, now());
    }

    public function scopeAll(Builder $query)
    {
        return $query;
    }

    public function scopePending(Builder $query)
    {
        return $query->whereIn('status', [
            UserOrder::ORDER_STATE_PAID
        ]);
    }

    public function scopeFinish(Builder $query)
    {
        return $query->whereIn('status', [
            UserOrder::ORDER_STATE_FINISHED,
            UserOrder::ORDER_STATE_COMMENTED,
        ]);
    }

    public function scopeSelectSubStatus(Builder $query)
    {
        $columns = [
            'finished', 'failed', 'cancelled', 'refunded', 'commented'
        ];

        foreach ($columns as $column) {
            $column_at = $column .'_at';
            $query->selectSub("count($column_at)", $column);
        }

        return $query;
    }

    public function scopeRenewalNotice(Builder $query)
    {
        return $query->where(function ($query) {
            $query->orWhereNull('cancel_renewal_notice')->orWhere('renewal_notice', 0);
        });
    }

    public function hasCancelRenewalNotice()
    {
        return !is_null($this->cancel_renewal_notice) || $this->renewal_notice > 0;
    }
}
