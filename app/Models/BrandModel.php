<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class BrandModel extends EloquentModel
{
    use SoftDeletes;

    protected $fillable = ['name', 'brand_id'];

    public function brand() {
        return $this->belongsTo(Brand::class);
    }
}
