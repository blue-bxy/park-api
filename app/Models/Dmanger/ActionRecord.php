<?php

namespace App\Models\Dmanger;

use App\Models\Admin;
use App\Models\EloquentModel;
use App\Models\Parks\Park;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class ActionRecord extends EloquentModel
{
    protected $table = 'activity_logs';
    // 添加时白名单
    protected $fillable=['causer_id','project_id','time'];

    public function admin()
    {
        return $this->belongsTo(Admin::class,'causer_id','id');
    }

    public function parks()
    {
        return $this->belongsTo(Park::class,'project_id','id');
    }

    // 搜索查询条件
    public function scopeSearch(Builder $query, Request $request)
    {
        // 操作人员
        if ($causer_id = $request->input('causer_id')) {
            $query->where('causer_id', $causer_id);
        }
        // 判断是否有project_id
        if ($project_id = $request->input('project_id')) {
            $query->where('project_id', $project_id);
        }
        // 判断是否有时间
        if ($time = $request->input('time')) {
            $query->where('created_at','>=',$time);
        }
        return $query;
    }
}
