<?php

namespace App\Models\Users;

use App\Models\Dmanger\CarAptOrder;
use App\Models\EloquentModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserRefund extends EloquentModel
{
	use SoftDeletes;

    public static $refundCateMaps=[
        1=>'预约',
        2=>'停车',
    ];

    public static $refundWayMaps=[
        1=>'原路退还',
        2=>'转账',
    ];

    public static $refundTypeMaps=[
        1=>'普通退款',
        2=>'赔付',
    ];

    public static $refundChannelsMaps=[
        1=>'微信',
        2=>'支付宝'
    ];

	protected $fillable = [
        'user_id', 'order_id','order_type', 'amount', 'refunded_amount', 'transfer_account', 'type',
        'refund_no', 'refund_id', 'reason','remarks','operator','refund_way','refund_channels','refund_category',
        'refunded_at', 'failed_at'
    ];

	protected $dates = [
	    'refunded_at', 'failed_at'
    ];

    public function order(){
        return $this->morphTo();
    }

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function getRefundCategoryRenameAttribute(){
        return array_get(self::$refundCateMaps,$this->refund_category);
    }

    public function getRefundWayAttribute($value){
        if(!empty($value)){
            return self::$refundWayMaps[$value];
        }
    }

    public function getTypeAttribute($value){
        return self::$refundTypeMaps[$value];
    }

    public function getRefundChannelsAttribute($value){
        if(!empty($value)){
            return self::$refundChannelsMaps[$value];
        }
    }

    public function hasRefund()
    {
        return !is_null($this->refunded_at);
    }

    //条件查询
    public function scopeSearch(Builder $query,Request $request){
        if($starttime=$request->input('starttime')){
            $query->where('refunded_at','>=',$starttime);
        }
        if($endtime=$request->input('endtime')){
            $query->where('refunded_at','<=',$endtime);
        }
        if($refund_no=$request->input('refund_no')){
            $query->where('refund_no','like',"%$refund_no%");
        }
        if($order_no=$request->input('order_no')){
            $query->whereHasMorph('order',[CarAptOrder::class],function ($query,$type) use ($order_no){
                if($type == CarAptOrder::class){
                    $query->where('no','like',"$order_no");
                }
            });
        }
        if($car_num=$request->input('car_num')){
            $query->whereHasMorph('order',[CarAptOrder::class],function ($query,$type) use ($car_num){
               if($type == CarAptOrder::class){
                   $query->whereHas('carApt',function ($query) use ($car_num){
                       $query->whereHas('car',function ($query) use ($car_num){
                            $query->where('car_number','like',"%$car_num%");
                       });
                   });
               }
            });
        }
        if($transaction_no=$request->input('transaction_no')){
            $query->whereHasMorph('order',[CarAptOrder::class],function ($query,$type) use ($transaction_no){
                if($type == CarAptOrder::class){
                   $query->where('transaction_id','like',"%$transaction_no%");
                }
            });
        }
        if($refund_status=$request->input('refund_status')){
            if($refund_status==1){
                $query->whereNotNull('refunded_at');
            }else{
                $query->whereNull('refunded_at');
            }
        }
        if($type=$request->input('type')){
            $query->where('type',$type);
        }
        if($refund_channels=$request->input('refund_channels')){
            $query->where('refund_channels',$refund_channels);
        }
        if($refund_way=$request->input('refund_way')){
            $query->where('refund_way',$refund_way);
        }
        if($operator=$request->input('operator')){
            $query->where('operator','like',"%$operator%");
        }
        return $query;
    }
}
