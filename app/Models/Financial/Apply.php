<?php

namespace App\Models\Financial;

use App\Models\EloquentModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Apply extends EloquentModel
{
    protected $fillable=['no','amount','payment_number','success_number','business_type','person_type',
                'submit','status','apply_time','payment_time','complete_time','agent','channel'
        ];

    public static $BusinessType=['1'=>'提现','2'=>'退款'];

    public static $PersonType=['1'=>'物业','2'=>'用户'];

    public static $Submit=['1'=>'待提交','2'=>'已提交','3'=>'已拒绝'];

    public static $Status=['1'=>'待处理','2'=>'处理中','3'=>'已处理'];

    public function getBusinessTypeAttribute($value){
        return self::$BusinessType[$value];
    }
    public function getPersonTypeAttribute($value){
        return self::$PersonType[$value];
    }
    public function getSubmitAttribute($value){
        return self::$Submit[$value];
    }
    public function getStatusAttribute($value){
        return self::$Status[$value];
    }

    public function scopeSearch(Builder $query,Request $request){
        if($no=$request->input('no')){
            $query->where('no','like',"%$no%");
        }
        if($submit=$request->input('submit')){
            $query->where('submit',$submit);
        }
        if($person_type=$request->input('person_type')){
            $query->where('person_type',$person_type);
        }
        if($start_time=$request->input('start_time')){
            $query->where('apply_time','>=',$start_time);
        }
        if($end_time=$request->input('end_time')){
            $query->where('apply_time','<=',$end_time);
        }
        if($business_type=$request->input('business_type')){
            $query->where('business_type',$business_type);
        }
        if($status=$request->input('status')){
            $query->where('status',$status);
        }
    }
}
