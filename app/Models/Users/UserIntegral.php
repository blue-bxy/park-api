<?php

namespace App\Models\Users;

use App\Models\EloquentModel;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserIntegral extends EloquentModel
{
    use HasApiTokens, Notifiable, SoftDeletes;
	
	protected $fillable = [
        'user_id', 'operation', 'integral_num', 'balance', 'order_type', 'order_id'
    ];
	
}
