<?php

namespace App\Models;


use App\Packages\Payments\Config;
use App\Packages\Payments\PaymentType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * @property int            $id
 * @property string         $user_id
 * @property string         $no
 * @property integer        $amount
 * @property integer        $paid_amount
 * @property string         $transaction_id
 * @property string         $refund_id
 * @property integer        $refund_amount
 * @property string         $refund_no
 * @property string         $currency
 * @property string         $status
 * @property string         $gateway
 * @property \Carbon\Carbon $paid_at
 * @property \Carbon\Carbon $refunded_at
 * @property \Carbon\Carbon $expired_at
 * @property \Carbon\Carbon $failed_at
 * @property User           $user
 */
class Recharge extends EloquentModel implements PaymentInterface
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
        'user_id', 'no', 'amount', 'paid_amount', 'transaction_id',
        'status', 'gateway', 'refund_id', 'refund_no',
        'refunded_at', 'paid_at', 'expired_at', 'failed_at',
    ];

    protected $casts = [
        'amount' => 'int',
        'paid_amount' => 'int'
    ];

    protected $dates = [
        'paid_at', 'expired_at', 'failed_at', 'refunded_at'
    ];

    protected static function boot()
    {
        parent::boot();

        static::created(function (Recharge $recharge) {
            $price = $recharge->decimalAmount();

            $type = PaymentType::PAYMENT_TYPE_CHARGE;
            /** @var Payment $payable */
            $recharge->payable()->create([
                'no' => $recharge->no,
                'amount' => $recharge->amount,
                'gateway' => $recharge->gateway,
                'user_id' => $recharge->user_id,
                'type' => $type,
                // 'gateway_order' => $recharge->getOriginal()
                'description' => "充值 - {$price}元",
                'context' => [
                    'title' => '充值',
                    'body' => "充值 - {$price}元",
                    'price' => $price,
                    'type' => $type
                ]
            ]);
        });
    }

    public function decimalAmount()
    {
        return decimal_number($this->amount / 100);
    }

    public function payable()
    {
        return $this->morphOne(Payment::class, 'payable');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * paid
     *
     * @param array $attributes
     * @throws \Throwable
     */
    public function paid(array $attributes)
    {
        $this->paid_amount = $attributes['total_amount'];
        $this->paid_at = now();
        $this->status = self::STATUS_PAID;
        $this->gateway = $attributes['gateway'];
        $this->transaction_id = $attributes['transaction_id'] ?? Str::orderedUuid();
        $this->save();
    }

    public function scopeSearch(Builder $query,Request $request){
        if($serial_number=$request->input('serial_number')){
            $query->where('transaction_id','like',"%$serial_number%");
        }
        if($account=$request->input('account')){
            $query->whereHas('user',function ($query) use($account){
               $query->where('nickname','like',"%$account%");
            });
        }
        if($starttime=$request->input('starttime')){
            $query->where('created_at','>=',$starttime);
        }
        if($endtime=$request->input('endtime')){
            $query->where('created_at','<=',$endtime);
        }
        if($user_id = $request->input('user_id')){
            $query->where('user_id',$user_id);
        }
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
        return PaymentType::PAYMENT_TYPE_CHARGE;
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
