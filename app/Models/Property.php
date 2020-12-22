<?php

namespace App\Models;

use App\Models\Bills\ParkWallet;
use App\Models\Parks\Park;
use App\Models\Traits\CommonUser;
use App\Models\Traits\HasRentalRate;
use App\Models\Users\UserParkingSpace;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Property extends AuthUser
{
    use HasApiTokens, Notifiable, SoftDeletes, CommonUser;

    public $guard_name = 'property';

    public static $logName = "property";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'account_name', 'code', 'mobile', 'email', 'password', 'park_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = [
        'recode'
    ];

    public function getRecodeAttribute()
    {
        return 10000 + $this->getkey();
    }

    /**
     * 管理的停车场
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function park() {
        return $this->belongsTo(Park::class);
    }

    public function userSpace()
    {
        return $this->hasMany(UserParkingSpace::class, 'park_id', 'park_id');
    }

    public function scopeSearch(Builder $query,Request $request)
    {
        if($name = $request->input('name')){
            $query->where('name','like',"%$name%");
        }

        if($code = $request->input('recode')){
            $query->where('code','like',"%$code%");
        }

        if($park_name = $request->input('park_name')){
            $query->whereHas('park',function ($query) use ($park_name){
                $query->where('project_name','like',"%$park_name%");
            });
        }
    }

    public function wallet()
    {
        return $this->hasOne(ParkWallet::class,'park_id','park_id');
    }
}
