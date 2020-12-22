<?php

namespace App\Models\Parks;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ModelHasParkRate extends Pivot
{
    protected $table = 'model_has_park_rates';

    protected $fillable = ['model_type', 'model_id', 'park_rate_id'];

    public $timestamps = false;

    public function model() {
        return $this->morphTo();
    }
}
