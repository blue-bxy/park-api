<?php

namespace App\Models\Traits;

use App\Models\Position;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait HasPosition
{
    /**
     * positions
     *
     * @return MorphToMany
     */
    public function positions()
    {
        return $this->morphToMany(Position::class, 'user', 'model_has_department_positions');
    }
}
