<?php

namespace App\Models\Traits;

use App\Models\Department;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait HasDepartment
{
    /**
     * departments
     *
     * @return MorphToMany
     */
    public function departments()
    {
        return $this->morphToMany(Department::class, 'user', 'model_has_departments');
    }

    public static function bootHasDepartment()
    {
        static::deleting(function ($model) {
            if (method_exists($model, 'isForceDeleting') && ! $model->isForceDeleting()) {
                return;
            }

            $model->departments()->detach();
        });
    }
}
