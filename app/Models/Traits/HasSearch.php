<?php

namespace App\Models\Traits;

use App\Models\Users\UserSearch;

/**
 * Trait HasSearch
 * @package App\Models\Traits
 *
 * @property UserSearch $searches
 */
trait HasSearch
{
    /**
     * searches
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function searches()
    {
        return $this->hasMany(UserSearch::class);
    }
}
