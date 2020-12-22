<?php

namespace App\Models;

use App\Models\Traits\CommonUser;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * Class Admin
 * @package App\Models
 *
 * @property-read int $recode
 */
class Admin extends AuthUser
{
    use HasApiTokens, Notifiable, SoftDeletes, CommonUser;

    public $guard_name = 'admin';

    public static $logName = "admin";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'account_name', 'code', 'mobile', 'email', 'password',
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
}
