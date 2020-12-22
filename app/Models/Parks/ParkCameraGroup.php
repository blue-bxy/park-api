<?php

namespace App\Models\Parks;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class ParkCameraGroup extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'unique_id', 'total_count', 'available_count', 'is_active', 'park_id', 'park_area_id'
    ];

    public function cameras() {
        return $this->hasMany(ParkCamera::class, 'group_id');
    }

    public function virtualSpace()
    {
        return $this->hasManyThrough(ParkVirtualSpace::class, ParkCamera::class, 'group_id');
    }

    public function scopeSearch(Builder $query, Request $request) {
        if ($name = $request->input('name')) {
            $query->where('name', 'like', "%$name%");
        }
        if ($unique_id = $request->input('unique_id')) {
            $query->where('unique_id', 'like', "%$unique_id%");
        }
        if ($park_area_id = $request->input('park_area_id')) {
            $query->where('park_area_id', '=', $park_area_id);
        }
        return $query;
    }
}
