<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Model;

class UserLoginLog extends Model
{
    protected $fillable = [
        'user_id', 'last_ip'
    ];
}
