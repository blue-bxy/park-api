<?php

namespace App\Models\Financial;

use App\Models\EloquentModel;
use App\Models\Coupons\Coupon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;


class Discount extends EloquentModel
{
    use SoftDeletes;

    protected $guarded = [];


    /**
     * 根据查询条件过滤查询范围
     * @param   Builder $query
     * @param   Request $request
     * @return  Builder
     */
    public function scopeSearch(Builder $query,Request $request){
        if ($project_name = $request->input('project_name')){
            $query->where('project_name','like','%$project_name%');
        }
        if ($issue_form = $request->input('issue_form')){
            $query->where('issue_form',$issue_form);
        }
        if ($use_state = $request->input('use_state')){
            $query->where('use_state',$use_state);
        }
        if ($discount_type = $request->input('discount_type')){
            $query->where('discount_type',$discount_type);
        }
        if ($discount_coupon_number = $request->input('discount_coupon_number')){
            $query->where('discount_coupon_number',$discount_coupon_number);
        }
        if ($issue_start_date = $request->input('issue_start_date')){
            $query->where('issue_start_date','>',$issue_start_date);
        }
        if ($issue_end_date = $request->input('issue_end_date')){
            $query->where('issue_end_date','<',$issue_end_date);
        }
    }

}
