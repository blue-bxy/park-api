<?php

namespace App\Models\Financial;

use App\Models\Parks\Park;
use App\Models\Property;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccountManage extends Model
{
    use SoftDeletes;

    // 添加时白名单
    protected $fillable=[
        'park_id','property_id','account_name','account','account_type','account_province','account_city',
        'bank_name','bank_code','sub_branch','contract_id','synchronization_type','banned_withdraw','audit_status'
    ];

    protected static $accountTypes = [ 1 => '对公', 2 => '对私'];

    protected static  $synchronizationTypes = [ 1 => '未同步', 2 => '已同步'];

    protected static $auditStatus = [ 1 => '未审核', 2 => '已审核'];


    public function getAccountTypeAttribute($value)
    {
        return array_get(self::$accountTypes,$value,'对公');
    }

    public function getAuditStatusAttribute($value)
    {
        return array_get(self::$auditStatus,$value,'未审核');
    }

    public function getSynchronizationTypeAttribute($value)
    {
        return array_get(self::$synchronizationTypes,$value,'未同步');
    }

    public function park()
    {
        return $this->belongsTo(Park::class,'park_id');
    }

    public function property()
    {
        return $this->belongsTo(Property::class,'property_id');
    }

    // public function contract()      // 暂时没有合同表，空着先
    // {
    //     return $this->belongsTo();
    // }


    public function scopeSearch(Builder $query,Request $request)
    {
        if($account_name = $request->input('account_name')){
            $query->where('account_name','like',"%$account_name%");
        }

        if($account = $request->input('account')){
            $query->where('account','like',"%$account%");
        }

        if($bank_name = $request->input('bank_name')){
            $query->where('bank_name','like',"%$bank_name%");
        }

        if($park_name = $request->input('park_name')){
            $query->whereHas('park', function ($query) use ($park_name) {
                $query->where('project_name','like',"%$park_name%");
            });
        }

        if($synchronization_type = $request->input('synchronization_type')){
            $query->where('synchronization_type',$synchronization_type);
        }

        if($account_type = $request->input('account_type')){
            $query->where('account_type',$account_type);
        }

        if($audit_status = $request->input('audit_status')){
            $query->where('audit_status',$audit_status);
        }

        return $query;
    }
}
