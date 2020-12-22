<?php

namespace App\Models\Users;

use App\Models\EloquentModel;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Parks\Park;

class UserCollect extends EloquentModel
{
    use HasApiTokens, Notifiable, SoftDeletes;

    protected $fillable = [
        'user_id',
        'park_id'
    ];

    /**
     * 获取停车场信息
     */
    public function parks()
    {
        return $this->belongsTo(Park::class, 'park_id', 'id');
    }
}
