<?php

namespace App\Models;

class Position extends EloquentModel
{
    protected $fillable = [
        'department_id', 'name', 'guard_name'
    ];

    protected $appends = [
        'guard_rename'
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
