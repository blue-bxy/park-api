<?php

namespace App\Models\Users;

use App\Models\EloquentModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserMessage extends EloquentModel
{
    use SoftDeletes;

	protected $fillable = [
        'user_id', 'title', 'content', 'imgurl', 'type', 'read_time',
        'source_type', 'source_id', 'message_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
	}

    public function message()
    {
        return $this->belongsTo(Message::class);
	}

    public function scopeRead(Builder $query)
    {
        return $query->whereNotNull('read_time');
	}

    public function scopeUnread(Builder $query)
    {
        return $query->whereNull('read_time');
	}

    public function hasRead()
    {
        return !is_null($this->read_time);
	}

    public function scopeCoupon(Builder $query)
    {
        return $query->type(4);
	}

    public function scopeOrder(Builder $query)
    {
        return $query->type(1);
	}

    public function scopeSystem(Builder $query)
    {
        return $query->type(0);
	}

    public function scopeType(Builder $query, $type = 0)
    {
        return $query->where('type', $type);
	}
}
