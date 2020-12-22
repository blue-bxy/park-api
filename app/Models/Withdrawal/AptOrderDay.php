<?php

namespace App\Models\Withdrawal;

use App\Models\EloquentModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class AptOrderDay extends EloquentModel
{
    protected $fillable=['park_id','no','type','amount','time'];

    public static $Type=[1=>'正常结算收入',2=>'延时结算收入',3=>'退款'];

    public function getTypeRenameAttribute(){
        return array_get(self::$Type,$this->type,'正常结算收入');
    }

//    public function getAmountAttribute($value){
//        return $this->formatAmount($value);
//    }

    public function scopeSearch(Builder $query,Request $request){
        if($type=$request->input('type')){
            $query->where('type',$type);
        }
    }
}
