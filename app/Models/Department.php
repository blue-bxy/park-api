<?php

namespace App\Models;

use Spatie\Permission\Traits\HasRoles;

class Department extends EloquentModel
{
    use HasRoles;

    protected $fillable = [
        'name', 'guard_name'
    ];

    protected $appends = [
        'guard_rename'
    ];

    public function positions()
    {
        return $this->hasMany(Position::class);
    }

    public function admin()
    {
        return $this->morphedByMany(Admin::class, 'user', 'model_has_departments');
    }

    public function properties()
    {
        return $this->morphedByMany(Property::class, 'user', 'model_has_departments');
    }
}
