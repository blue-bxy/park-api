<?php

namespace App\Models\Users;

use App\Models\EloquentModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserDevice extends EloquentModel
{
    use SoftDeletes;

    protected $fillable =  [
        'user_id', 'uid', 'platform', 'version', 'brand', 'model', 'jpush_id'
    ];

    protected static function boot()
    {
        parent::boot();

        static::saved(function ($device) {
            if ($device->user_id && $device->jpush_id) {
                app('jpush.device')->updateAlias($device->jpush_id, $device->user_id);
            }
        });
    }
}
