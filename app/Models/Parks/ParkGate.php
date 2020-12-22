<?php

namespace App\Models\Parks;

use App\Models\EloquentModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class ParkGate extends EloquentModel
{
    const PROGRAMMES = [
        1 => '科拓云',
        2 => '杰停云',
        3 => '科拓场库'
    ];

    const MODES = [
        1 => '云端转发',
        2 => '场库直发'
    ];

    const PAYMENT_MODES = [
        1 => '场库自收',
        2 => '平台代收',
        3 => '预约订单平台代收',
        4 => '共享车位订单平台代收'
    ];

    use SoftDeletes;

    protected $fillable = [
        'park_id', 'programme', 'brand', 'version', 'mode', 'payment_mode', 'is_active'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function park() {
        return $this->belongsTo(Park::class);
    }

    /**
     * 查询过滤
     * @param Builder $query
     * @param Request $request
     */
    public function scopeSearch(Builder $query, Request $request) {
        if ($parkId = $request->input('park_id')) {
            $query->where('park_id', '=', $parkId);
        }
        if ($projectName = $request->input('project_name')) {
            $query->whereHas('park', function (Builder $query) use ($projectName) {
                $query->where('project_name', 'like', "%$projectName%");
            });
        }
        if ($brand = $request->input('brand')) {
            $query->where('brand', 'like', "%$brand%");
        }
        if (!is_null($isActive = $request->input('is_active'))) {
            $query->where('is_active', '=', $isActive);
        }
        if ($programme = $request->input('programme')) {
            $query->where('programme', '=', $programme);
        }
        if ($paymentMode = $request->input('payment_mode')) {
            $query->where('payment_mode', '=', $paymentMode);
        }
    }
}
