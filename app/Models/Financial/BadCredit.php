<?php

namespace App\Models\Financial;

use App\Models\EloquentModel;
use App\Models\Users\UserOrder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class BadCredit extends EloquentModel
{
    protected $fillable=['order_id','bad_amount','already_amount','is_payment','bad_results','bad_source'];

    use SoftDeletes;

    public static $paymentMap=[
      '1'=>'否',
      '2'=>'是'
    ];

    public function order(){
        return $this->belongsTo(UserOrder::class,'order_id','id');
    }

    public function getBadAmountAttribute($value){
        return $this->formatAmount($value);
    }

    public function getAlreadyAmountAttribute($value){
        return $this->formatAmount($value);
    }

    public function getIsPaymentAttribute($value){
        return self::$paymentMap[$value];
    }

    public function scopeSearch(Builder $query,Request $request){
        if($order_no=$request->input('order_no')){
            $query->whereHas('order',function ($query) use ($order_no){
               $query->where('order_no','like',"%$order_no%");
            });
        }
        if($mobile=$request->input('mobile')){
            $query->whereHas('order',function ($query) use ($mobile){
                $query->whereHas('user',function ($query) use ($mobile){
                   $query->where('mobile','like',"%$mobile%");
                });
            });
        }
        if($car_num=$request->input('car_num')){
            $query->whereHas('order',function ($query) use ($car_num){
                $query->whereHas('car',function ($query) use ($car_num){
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
}
