<?php

namespace App\Models\Traits;

use App\Models\Users\UserCollect;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * Trait HasCollect
 * @package App\Models\Traits
 *
 * @property UserCollect|Collection $collect
 */
trait HasCollect
{
    /**
     * collect
     *
     * @return HasMany
     */
    public function collect()
    {
        return $this->hasMany(UserCollect::class);
    }

    public function favorite($park_id)
    {
        if (auth()->guest()) {
            return false;
        }

        if ($this->relationLoaded('collect')) {
            return $this->collect->filter(function ($collect) use ($park_id) {
                return $collect->park_id == $park_id;
            })->isNotEmpty();
        }

        return $this->collect()->where('park_id', $park_id)->exists();
    }
}
