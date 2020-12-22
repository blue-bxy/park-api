<?php

namespace App\Models\Users;

use App\Models\Bills\OrderAmountDivide;
use App\Models\Dmanger\CarApt;
use App\Models\EloquentModel;
use App\Models\Financial\Withdrawal;
use App\Models\Parks\Park;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ParkingSpaceRentalBill extends EloquentModel
{
    protected $fillable = [
        'user_id', 'park_id', 'no', 'body',  'amount', 'fee', 'rental_amount', 'type'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->morphTo();
    }

    public function park()
    {
        return $this->belongsTo(Park::class);
    }

    public function scopeSearch(Builder $query,Request $request){
        if($account=$request->input('account')){
            $query->whereHas('user',function ($query) use($account){
               $query->where('nickname','like',"%$account%");
            });
        }
    }
}
