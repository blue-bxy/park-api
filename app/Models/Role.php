<?php

namespace App\Models;

use App\Services\PermissionService;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    protected $appends = [
        'guard_rename'
    ];

    public function getGuardRenameAttribute()
    {
        return \Arr::get(PermissionService::$guards, $this->guard_name, '云端');
    }

    /**
     * departments
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function departments()
    {
        return $this->morphedByMany(
            Department::class,
            'model',
            'model_has_roles',
            'role_id',
            'model_id'
        );
    }
}
