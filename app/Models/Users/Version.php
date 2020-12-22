<?php

namespace App\Models\Users;

use App\Models\Admin;
use App\Models\EloquentModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class Version extends EloquentModel
{
    use SoftDeletes;

	protected $fillable = [
        'version_no', 'platform', 'update_description', 'resource_url', 'is_force','user_id'
    ];

	protected $casts = [
	    'is_force' => 'boolean',
        'resource_url' => 'array'
    ];

    public static function lastVersion($platform)
    {
        $query = static::query();

        $query->where('platform', $platform);

        $query->select('version_no as version', 'is_force');

        $version = $query->latest()->first();

        return $version ?? [
            'version' => '1.0',
            'is_force' => false,
        ];
	}

    public function user()
    {
        return $this->belongsTo(Admin::class,'user_id');
	}

    /**
     * 搜索查询
     * @param Builder $query
     * @param Request $request
     */
    public function scopeSearch(Builder $query,Request $request)
    {
        if($start_time = $request->input('start_time')){
            $query->where('created_at','>=',$start_time);
        }

        if($end_time = $request->input('end_time')){
            $query->where('created_at','>=',$end_time);
        }

        if($version_no = $request->input('version_no')){
            $query->where('version_no',$version_no);
        }

        if($user_name = $request->input('user_name')){
            $query->whereHas('user',function($query)use($user_name){
                $query->where('name',$user_name);
            });
        }

        if($platform = $request->input('platform')){
            $query->where('platform',$platform);
        }
	}
}
