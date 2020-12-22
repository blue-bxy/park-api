<?php

namespace App\Models\Parks;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ParkMapSpace extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'unique_id', 'number', 'floor', 'area_code', 'park_id', 'park_area_id'
    ];

    public function park() {
        return $this->belongsTo(Park::class);
    }

    public function area() {
        return $this->belongsTo(ParkArea::class);
    }
}
