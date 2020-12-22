<?php

namespace App\Models\Dmanger;

use App\Models\EloquentModel;
use App\Models\Parks\Park;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class ParkIncome extends EloquentModel
{
    // 软删除
    use SoftDeletes;
    protected $table = "user_orders";
    // 添加时白名单
    protected $fillable=[
        'order_no', 'user_id', 'park_id', 'coupon_id', 'car_stop_id', 'user_car_id', 'car_apt_id',
        'subscribe_amount', 'amount', 'discount_amount', 'refund_amount', 'total_amount',
        'payment_no', 'payment_gateway', 'status',
        'paid_at', 'cancelled_at', 'refunded_at', 'finished_at', 'failed_at', 'commented_at'
        ];
    //修改器
//    public function getSubscribeAmountAttribute($value){
//        return $this->formatAmount($value);
//    }
//    public function getRefundAmountAttribute($value){
//        return $this->formatAmount($value);
//    }
//    public function getAmountAttribute($value){
//        return $this->formatAmount($value);
//    }
//    public function getDiscountAmountAttribute($value){
//        return $this->formatAmount($value);
//    }
    public function getCreatedAtAttribute($value){
        return date('Y-m-d H:i:s',strtotime($value));
    }
    // 关联模型
    public function parks()
    {
        return $this-> belongsTo(Park::class,'park_id','id');
    }
    public function carStop()
    {
        return $this->belongsTo(CarStop::class,'car_stop_id','id');
    }

    public function carApt()
    {
        return $this->belongsTo(CarApt::class,'car_apt_id','id');
    }
    // 车场收入搜索
    public function scopeSearch(Builder $query,Request $request)
    {
        // 判断是否有park_id
        if ($parkId = $request->input('park_id')) {
            $query->where('park_id', $parkId);
        }
        // 判断是否有订单号
        if ($orderNo = $request->input('order_no')) {
            $query->where('order_no', 'like',"%{$orderNo}%");
        }
        // 判断是否有时间段
        if ($apt_start_time = $request->input('start_time')) {
            $query->where('created_at', '>=',$apt_start_time);
        }
        if ($apt_end_time = $request->input('end_time')) {
            $query->where('created_at','<=', $apt_end_time);
        }
        return $query;
    }
}
