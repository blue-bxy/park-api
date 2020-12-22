<?php

namespace App\Models\Users;

use App\Models\EloquentModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserSearch extends EloquentModel
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'keyword', 'click_num', 'longitude', 'latitude'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 最近的搜索排序
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeRecent(Builder $query)
    {
        return $query->latest(self::UPDATED_AT);
    }
}
