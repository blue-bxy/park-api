<?php

namespace App\Models;

use App\Models\Dmanger\CarApt;
use App\Models\Dmanger\CarRent;
use App\Models\Dmanger\CarStop;
use App\Models\Parks\Park;
use App\Models\Parks\ParkSpace;
use App\Models\Traits\CanCacheField;
use App\Models\Traits\HasAmount;
use App\Models\Traits\HasAuthAccount;
use App\Models\Traits\HasCollect;
use App\Models\Traits\HasRentalRate;
use App\Models\Traits\HasParkingLotOpenApply;
use App\Models\Traits\HasPayment;
use App\Models\Traits\HasRental;
use App\Models\Traits\HasSearch;
use App\Models\Users\ParkingSpaceRentalBill;
use App\Models\Users\ParkingSpaceRentalRecord;
use App\Models\Users\UserAccount;
use App\Models\Users\UserAuthAccount;
use App\Models\Users\UserComment;
use App\Models\Users\UserComplaint;
use App\Models\Users\UserLoginLog;
use App\Models\Users\UserOrder;
use App\Models\Users\UserMessage;
use App\Models\Users\UserParkingSpace;
use App\Models\Users\UserPaymentLog;
use App\Models\Users\UserCar;
use App\Models\Users\UserCoupon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Users\UserConsumptionRecodes;


/**
 * Class User
 * @package App\Models
 * @property string $nickname
 * @property string $mobile
 * @property int $integral
 * @property array $cache
 * @property int $balance
 * @property int $rental_amount
 */
class User extends AuthUser
{
    use HasApiTokens, Notifiable, SoftDeletes, CanCacheField, HasPayment, HasAuthAccount, HasParkingLotOpenApply,
        HasAmount, HasCollect, HasSearch, HasRental;

    const GENDER_NULL = 0;
    const GENDER_MAN = 1;
    const GENDER_WOMAN = 2;

    public static $gender = [
        self::GENDER_NULL => '未知',
        self::GENDER_MAN => '男',
        self::GENDER_WOMAN => '女',
    ];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nickname', 'mobile', 'password', 'sex', 'email', 'headimgurl', 'address',
        'integral', 'balance', 'rental_amount', 'cache',
        'is_verify', 'verified_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'is_verify' => 'boolean',
        'cache' => 'array'
    ];

    const CACHE_FIELDS = [
        'balance' => [
            'total' => 0,
            'used' => 0,
        ],

        'rental_amount' => [
            'total' => 0,
            'used' => 0,
        ]
    ];

	/**
     * 获取用户订单的评论
     */
    public function comments()
    {
        return $this->hasMany(UserComment::class);
    }
    /**
     * 获取消息列表
     */
    public function messages()
    {
        return $this->hasMany(UserMessage::class);
    }
    /**
     * 获取投诉列表
     */
    public function complaints()
    {
        return $this->hasMany(UserComplaint::class);
    }
	/**
     * 获取用户订单
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders()
    {
        return $this->hasMany(UserOrder::class);
    }
    /**
     * 获取停车场信息
     */
    public function parks()
    {
        return $this->hasMany(Park::class);
    }
	/**
     * 获取用户支付记录
     */
    public function paymentLogs()
    {
        return $this->hasMany(UserPaymentLog::class);
    }

    /**
     * cars
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cars()
    {
        return $this->hasMany(UserCar::class);
    }

    /**
     * 发布的车位
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function parkSpace() {
        return $this->morphMany(ParkSpace::class, 'user');
    }

    /**
     * coupon
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function coupon()
    {
        return $this->hasMany(UserCoupon::class);
    }

    /**
     * validCoupon
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function validCoupon()
    {
        return $this->coupon()->valid();
    }

    public function recharges()
    {
        return $this->hasMany(Recharge::class);
    }

    public function accounts()
    {
        return $this->hasMany(UserAuthAccount::class);
    }

    public function space()
    {
        return $this->hasMany(UserParkingSpace::class);
    }

    /**
     * 发布出租记录
     *
     * @return \Illuminate\Database\Eloquent\Relations\morphMany
     */
    public function rents()
    {
        return $this->morphMany(CarRent::class, 'user');
    }

    public function subscribe()
    {
        return $this->hasMany(CarApt::class);
    }

    public function stops()
    {
        return $this->hasMany(CarStop::class);
    }

    public function UserAccount(){
        return $this->hasOne(UserAccount::class);
    }

    public function loginLogs() {
        return $this->hasMany(UserLoginLog::class);
    }

    public function getAvatarAttribute()
    {
        return \Storage::disk('public')->url("avatar/". $this->headimgurl);
    }

	/**
     * 获取用户消费记录
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userConsumptionRecodes()
    {
        return $this->hasMany(UserConsumptionRecodes::class);
    }

    public function amount()
    {
        return $this->balance;
    }

    public function avatar()
    {
        return $this->headimgurl;
    }

    public function hasBindMobile()
    {
        return (bool) $this->mobile;
    }

    public function updateOrCreateAccount(array $attributes, array $values = [])
    {
        return $this->accounts()->updateOrCreate($attributes, $values);
    }

    public function getGenderAttribute()
    {
        return static::$gender[$this->sex];
    }
}
