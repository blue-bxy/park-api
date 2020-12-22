<?php

namespace App\Models\Users;

use App\Models\EloquentModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserPaymentLog extends EloquentModel
{
    use HasApiTokens, Notifiable, SoftDeletes;

    public static $logName = "userPaymentLog";

	protected $fillable = [
        'user_id', 'order_no', 'trade_no', 'buyer_account', 'arrival_account', 'money_amount',
        'request_info', 'callback_info', 'account_type','business_type','order_type','order_id'
    ];
	public static $BusinessType=['1'=>'充值','2'=>'支付','3'=>'提现','4'=>'退款' ];

	public static $AccountType=['1'=>'余额','2'=>'微信','3'=>'支付宝'];

	public static $PayType=['1'=>'余额抵扣','2'=>'第三方抵扣','3'=>'积分抵扣'];

    public function getBusinessTypeRenameAttribute()
    {
        return array_get(self::$BusinessType, $this->business_type);
    }

    public function getAccountTypeRenameAttribute()
    {
        return array_get(self::$AccountType, $this->account_type);
    }
    public function getPayTypeRenameAttribute()
    {
        return array_get(self::$PayType, $this->pay_type);
    }

    public function getMoneyAmountAttribute($value){
        return $this->formatAmount($value);
    }
    public function getCreatedAtAttribute($value){
        return date('Y-m-d H:i:s', strtotime($value));
    }

	public function order()
    {
        return $this->morphTo();
    }

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function scopeSearch(Builder $query,Request $request)
    {
        if($user_name= $request->input('user_name')){
            $query->whereHas('user',function ($query) use ($user_name){
               $query->where('nickname','like',"%$user_name%");
            });
        }

        if($order_no = $request->input('order_no')){
            $query->where('order_no',$order_no);
        }

        if($business_type = $request->input('business_type')){
            $query->where('business_type',$business_type);
        }

        if($start_time = $request->input('start_time')){
            $query->where('created_at','>=',$start_time);
        }

        if($end_time = $request->input('end_time')){
            $query->where('created_at','<=',$end_time);
        }

        return $query;
    }


}
