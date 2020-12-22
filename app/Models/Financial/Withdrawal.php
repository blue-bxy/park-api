<?php

namespace App\Models\Financial;

use App\Models\Admin;
use App\Models\EloquentModel;
use App\Models\Parks\Park;
use App\Models\Property;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class Withdrawal extends EloquentModel
{
    use SoftDeletes;

    public static $statusMap = [
        1 => '待处理',
        2 => '汇款中',
        3 => '已完成',
    ];

    public static $personTypes = [
        1 => '物业提现',
        2 => '用户提现',
    ];

    public static $businessTypes = [
        1 => '物业贷款',
        2 => '车位出租收益',
    ];

    protected $fillable=[
        'withdrawal_no','person_type','apply_time','apply_money','park_id',
        'admin_id','audit_time','remark','completion_time','status','account',
        'user_type','user_id','business_type', 'gateway', 'account_name'
    ];

    //关联停车场模型
    public function user()
    {
        return $this->morphTo();
    }

    //关联停车场模型
    public function park()
    {
        return $this->belongsTo(Park::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }
    //修改器
    public function getStatusRenameAttribute()
    {
        return array_get(self::$statusMap, $this->status, '待处理');
    }

    public function getBusinessTypeRenameAttribute()
    {
        return array_get(self::$businessTypes, $this->business_type, '物业贷款');
    }

    public function getApplyMoneyAttribute($value)
    {
        return $this->formatAmount($value);
    }

    //条件查询
    public function scopeSearch(Builder $query, Request $request)
    {
        if ($person_type = $request->input('type')) {
            $query->where('person_type', $person_type);
        }
        if (is_numeric($status = $request->input('status'))) {
            $query->where('status', $status);
        }
        if($withdrawal_no=$request->input('withdrawal_no')){
            $query->where('withdrawal_no','like',"%$withdrawal_no%");
        }
        if(is_numeric($adjust_status=$request->input('adjust_status'))){
            if($adjust_status==1){
                $query->whereNotNull('admin_id');
            }elseif ($adjust_status==2){
                $query->whereNull('admin_id');
            }
        }
        if ($project_name = $request->input('project_name')) {
            $query->whereHas('park', function ($query) use ($project_name) {
                $query->where('project_name', 'like', "%$project_name%");
            });
        }
        // 根据用户昵称来查找
        if ($applicant = $request->input('nickname')) {
            $query->whereHasMorph('user', [User::class, Property::class], function ($query, $type) use ($applicant) {
                if ($type == User::class) {
                    return $query->where('nickname', 'like', "%$applicant%");
                }

                return $query->where('name', 'like', "%$applicant%");
            });
        }

        if ($mobile = $request->input('mobile')) {
            $query->whereHasMorph('user', [User::class, Property::class], function ($query, $type) use ($mobile) {
                return $query->where('mobile', 'like', "%$mobile%");
            });
        }

        if ($starttime = $request->input('starttime')) {
            $query->where('apply_time', '>=', $starttime);
        }
        if ($endtime = $request->input('endtime')) {
            $query->where('apply_time', '<=', $endtime);
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
