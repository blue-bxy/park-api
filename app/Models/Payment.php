<?php

namespace App\Models;

use App\Exceptions\ApiResponseException;
use App\Exceptions\PaymentOrderNotFundException;
use App\Models\Dmanger\CarAptOrder;
use App\Packages\Payments\Config;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * @property int            $id
 * @property string         $user_id
 * @property string         $no
 * @property string         $payable_id
 * @property string         $payable_type
 * @property integer        $amount
 * @property integer        $paid_amount
 * @property string         $description
 * @property string         $transaction_id
 * @property string         $refund_id
 * @property integer        $refund_amount
 * @property string         $refund_no
 * @property string         $currency
 * @property string         $status
 * @property string         $gateway
 * @property string         $type
 * @property array          $gateway_order
 * @property array          $context
 * @property array          $original_result
 * @property \Carbon\Carbon $paid_at
 * @property \Carbon\Carbon $refunded_at
 * @property \Carbon\Carbon $expired_at
 * @property \Carbon\Carbon $failed_at
 * @property Recharge|CarAptOrder       $payable
 * @property User           $user
 * @property-read boolean   $has_paid
 */
class Payment extends EloquentModel implements PaymentInterface
{
    use SoftDeletes;

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
        'user_id', 'no', 'payable', 'amount', 'paid_amount',
        'description', 'currency', 'status', 'gateway', 'type',
        'gateway_order', 'context', 'original_result',
        'refund_id', 'refund_no',
        'refunded_at', 'paid_at', 'expired_at', 'failed_at',
    ];

    protected $casts = [
        'amount' => 'int',
        'paid_amount' => 'int',
        'gateway_order' => 'array',
        'context' => 'array',
        'original_result' => 'array',
    ];

    protected $dates = [
        'paid_at', 'expired_at', 'failed_at', 'refunded_at'
    ];

    /**
     * payable
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function payable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function no()
    {
        return $this->no;
    }

    public function body()
    {
        return $this->description;
    }

    public function totalAmount()
    {
        return $this->amount;
    }

    public function getAttach(Request $request)
    {
        return [
            'type' => data_get($this->context, 'type'),
            'gateway' => $request->input('gateway')
        ];
    }
    /**
     * 是否已付款
     *
     * @return bool
     */
    public function getHasPaidAttribute()
    {
        return (bool) $this->paid_at;
    }

    /**
     * paid
     *
     * @param array $attributes
     * @return Payment
     * @throws \Throwable
     */
    public function paid(array $attributes)
    {
        if ($this->has_paid) {
            throw new PaymentOrderNotFundException();
        }

        return \DB::transaction(function () use ($attributes) {
            $this->paid_at = now();
            $this->status = self::STATUS_PAID;
            $this->gateway = $attributes['gateway'];
            $this->paid_amount = $attributes['total_amount'];
            $this->transaction_id = $attributes['transaction_id'] ?? Str::orderedUuid();
            $this->original_result = $attributes['origin_result'] ?? [];
            $this->save();

            //更新用户余额
            if ($attributes['gateway'] != Config::DEFAULT_CHANNEL) {
                $this->user->income($this, $attributes['total_amount']);
            }

            if ($this->user->balance < $this->payable->amount()) {
                throw new ApiResponseException('资金不足，无法完成扣款', 10001);
            }

            $this->payable->paid($attributes);

            return $this;
        });
    }


    public function scopeSearch(Builder $query,Request $request){
        if($order_no=$request->input('order_no')){
            $query->where('no','like',"%$order_no%");
        }
        if($mobile=$request->input('mobile')){
            $query->whereHas('user',function ($query) use ($mobile){
                $query->where('mobile','like',"%$mobile%");
            });
        }
        if($car_num=$request->input('car_num')){
            $query->whereHas('user',function ($query) use ($car_num){
                $query->whereHas('cars',function ($query) use ($car_num){
                    $query->where('car_number','like',"%$car_num%");
                });
            });
        }
        if($bad_results=$request->input('bad_results')){
            $query->where('bad_results',$bad_results);
        }
        if($bad_source=$request->input('bad_source')){
            $query->where('bad_source',$bad_source);
        }
        if($is_payment=$request->input('is_payment')){
            $query->where('is_payment',$is_payment);
        }
        if ($start_time = $request->input('start_time')) {
            $query->where('created_at', '>=', $start_time);
        }
        if ($end_time = $request->input('end_time')) {
            $query->where('created_at', '<=', $end_time);
        }
        return $query;
    }

    public function cancel()
    {
        \DB::transaction(function () {
            $this->status = self::STATUS_CANCELLED;
            $this->failed_at = now();

            $this->save();
        });
    }

    public function scopeCancel(Builder $query)
    {
        $query->update([
            'status' => self::STATUS_CANCELLED,
            'failed_at' => now()
        ]);
    }

    public function gateway()
    {
        return $this->gateway;
    }

    public function amount()
    {
        return $this->amount;
    }

    public function type()
    {
        return $this->type;
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
