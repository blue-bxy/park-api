<?php

namespace App\Models\Financial;

use App\Models\EloquentModel;
use Illuminate\Database\Eloquent\Model;

class Record extends EloquentModel
{
    protected $fillable=[
        'record_no','withdrawal_id','adjust_amount','adjust_type',
        'reason','is_loss','operator'
    ];
    public static $TypeMaps=[
        '0'=>'',
        '1'=>'结算扣款',
        '2'=>'结算补款'
    ];
    public static $LossMaps=[
        '0'=>'',
        '1'=>'否',
        '2'=>'是'
    ];
    public function getParkFeeAttribute($value){
        return $this->formatAmount($value);
    }

    public function getAdjustAmountAttribute($value){
        return $this->formatAmount($value);
    }

    public function getAdjustTypeAttribute($value){
        return self::$TypeMaps[$value];
    }
    public function getIsLossAttribute($value){
        return self::$LossMaps[$value];
    }


    public function withdrawal(){
        return $this->belongsTo(Withdrawal::class,'withdrawal_id','id');
    }
}
