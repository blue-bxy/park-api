<?php

namespace App\Models\Financial;

use App\Models\Admin;
use App\Models\EloquentModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class ReminderRecord extends EloquentModel
{
    use SoftDeletes;

    public static $logName = "reminder_record";

    protected $fillable = ['reminder_id','admin_id','state','feedback','reminder_time'];

    public function reminder()
    {
        return $this->belongsTo(Reminder::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function scopeSearch(Builder $query,Request$request)
    {
        if($order_no = $request->input('order_no')) {
            $query->whereHas('reminder',function ($query) use($order_no){
                $query->where('order_no', 'like', "%$order_no%");
            });
        }

        if($phone = $request->input('phone')) {
            $query->whereHas('reminder',function ($query) use($phone){
                $query->where('phone', 'like', "%$phone%");
            });
        }

        if($project_name = $request->input('park_name')){
            $query->whereHas('reminder',function ($query) use($project_name){
                $query->whereHas('park',function($query) use($project_name){
                    $query->where('project_name','like',"%$project_name%");
                });
            });
        }

        if($state = $request->input('state')){
            $query->where('state',$state);
        }

        // 逾期日期
        if($start_days_overdue= $request->input('start_days_overdue')){
            $query->whereHas('reminder',function ($query) use($start_days_overdue){
                $query->where('days_overdue','>=',$start_days_overdue);
            });
        }

        if($end_days_overdue= $request->input('end_days_overdue')){
            $query->whereHas('reminder',function ($query) use($end_days_overdue){
                $query->where('days_overdue','<=',$end_days_overdue);
            });
        }
    }
}
