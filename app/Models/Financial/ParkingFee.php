<?php

namespace App\Models\Financial;

use App\Models\Admin;
use App\Models\Parks\Park;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class ParkingFee extends Model
{
    protected $fillable=['park_id','fee','user_id'];

    public function admin(){
        return $this->belongsTo(Admin::class,'user_id');
    }
    public function park(){
        return $this->belongsTo(Park::class,'park_id');
    }

    public function scopeSearch(Builder $query,Request $request){
        if($park_name=$request->input('park_name')){
            $query->whereHas('park',function ($query)use ($park_name){
               $query->where('project_name','like',"%$park_name%");
            });
        }
    }
}
