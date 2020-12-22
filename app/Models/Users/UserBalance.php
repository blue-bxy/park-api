<?php

namespace App\Models\Users;

use App\Models\EloquentModel;
use App\Models\User;
use App\Packages\Payments\Gateway;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

/**
 * Class UserBalance
 * @package App\Models\Users
 *
 * @property int $status
 * @property-read string $status_rename
 */
class UserBalance extends EloquentModel
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'order_no', 'trade_no', 'type', 'gateway', 'body', 'trade_type', 'amount', 'balance', 'fee', 'status'
    ];

    public static $stateMaps = [
        0 => '申请中',
        1 => '成功',
        2 => '失败',
    ];

    public static $types = [
        1 => ['charge'],
        2 => ['subscribe','subscribe_renewal'],
        3 => ['withdraw'],
        4 => ['subscribe_refund']
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->morphTo();
    }

    public function scopeMonth(Builder $query, $month)
    {
        return $query->whereMonth($this->getCreatedAtColumn(), $month);
    }

    public function scopeYear(Builder $query, $year)
    {
        return $query->whereYear($this->getCreatedAtColumn(), $year);
    }

    public function getGatewayRenameAttribute()
    {
        return Gateway::getTypeName($this->gateway);
    }

    public function getStatusRenameAttribute()
    {
        return static::$stateMaps[$this->status];
    }

    public function scopeSearch(Builder $query,Request $request)
    {
        if($user_name= $request->input('user_name')){
            $query->whereHas('user',function ($query) use ($user_name){
                $query->where('nickname','like',"%$user_name%");
            });
        }

        if($order_no = $request->input('no')){
            $query->where('order_no',$order_no);
        }

        if($business_type = $request->input('business_type')){
            $query->whereIn('type',self::$types[$business_type]);
        }

        if($start_time = $request->input('start_time')){
            $query->where('created_at','>=',$start_time);
        }

        if($end_time = $request->input('end_time')){
            $query->where('created_at','<=',$end_time);
        }

        if($id = $request->input('id')){
            $query->where('id',$id);
        }

        return $query;
    }

}
