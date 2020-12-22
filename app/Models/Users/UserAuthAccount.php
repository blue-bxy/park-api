<?php

namespace App\Models\Users;

use App\Models\EloquentModel;
use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserAuthAccount extends EloquentModel
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'from', 'openid', 'unionid', 'nickname', 'avatar', 'sex', 'province', 'city',
        'access_token', 'access_token_expired_at', 'refresh_token',
        'raw'
    ];

    protected $casts = [
        'raw' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
