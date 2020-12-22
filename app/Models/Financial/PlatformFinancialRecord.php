<?php

namespace App\Models\Financial;

use App\Models\EloquentModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class PlatformFinancialRecord extends EloquentModel
{
    protected $fillable=['platform','business','type','income','spending','balance','date','state'];

    public static $Business=[1=>'电子预约费'];

    public static $Type=[1=>'正常预约费结算',2=>'延迟预约费结算',3=>'当天停车费退款',4=>'车场提现'];

    public function getBusinessRenameAttribute(){
        return array_get(self::$Business,$this->business,'电子预约费');
    }

    public function getTypeRenameAttribute(){
        return array_get(self::$Type,$this->type,'正常预约费结算');
    }

    public function getIncomeAttribute($value){
        return $this->formatAmount($value);
    }

    public function getSpendingAttribute($value){
        return $this->formatAmount($value);
    }

    public function getBalanceAttribute($value){
        return $this->formatAmount($value);
    }

    public function scopeSearch(Builder $query,Request $request){
        if($state=$request->input('state')){
            $query->where('state',$state);
        }
        if($business=$request->input('business')){
            $query->where('business',$business);
        }
        if($start_time=$request->input('start_time')){
            $query->where('date','>=',$start_time);
        }
        if($end_time=$request->input('end_time')){
            $query->where('date','<=',$end_time);
        }
        return $query;
    }
}
