<?php

namespace App\Models;

use App\Models\Traits\ModelTree;
use App\Services\PermissionService;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    use ModelTree;

    protected $appends = [
        // 'guard_rename'
    ];

    protected $casts = [
        'is_menu' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot(); // TODO: Change the autogenerated stub

        parent::saving(function ($permission) {
            $path = null;
            $parent_id = $permission->parent_id;

            if ($parent_id != 0) {
                $parent = Permission::find($parent_id);

                $path = $parent_id;

                if ($parent) {
                    $path = $parent->path ? $parent->path .','.$parent_id : $path;
                }
            }
            $permission->path = $path;
        });
    }

    public function getGuardRenameAttribute()
    {
        return PermissionService::$guards[$this->guard_name];
    }

    /**
     * @param $value
     */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = ($value == '#') ? '#-'.time() : $value;
    }

    /**
     * @param $value
     * @return mixed
     */
    public function getNameAttribute($value)
    {
        if (starts_with($value, '#')) {
            return head(explode('-', $value));
        }
        return $value;
    }

    /**
     * node
     *
     * @param Builder $query
     * @param $parent_id
     * @return Builder
     */
    public function scopeNode(Builder $query, $parent_id)
    {
        return $query->whereRaw("find_in_set({$parent_id}, path)");
    }

    public function scopeSelectColumns(Builder $query)
    {
        return $query->select('id', 'parent_id', 'sort', 'display_name', 'name', 'is_menu', 'level', 'guard_name');
    }
    /**
     * getPermissionsByGuard
     *
     * @param $guard
     * @param \Closure|null $callback
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public static function getPermissionsByGuard($guard, \Closure $callback = null)
    {
        $query = Permission::query();

        $query->where('guard_name', $guard);

        $query->selectColumns();

        $query->oldest('sort');

        if ($callback) {
            $query->tap($callback);
        }

        return $query->get();
    }
}
