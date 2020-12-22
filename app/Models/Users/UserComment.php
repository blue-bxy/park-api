<?php

namespace App\Models\Users;

use App\Models\EloquentModel;
use App\Models\Parks\Park;
use App\Models\Traits\HasGreen;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserComment extends EloquentModel implements RoaGreenInterface
{
    use SoftDeletes, HasGreen;

    public static $statusMaps = [
        1 => '待审核',
        2 => '已通过',
        3 => '未通过'
    ];

    protected $fillable = [
        'user_id', 'order_id', 'content', 'rate', 'is_display', 'imgurl',
        'audit_status', 'audit_time', 'refuse_reason', 'auditor', 'park_id',
        'suggestion', 'label', 'response'
    ];

    protected $casts = [
        'imgurl' => 'array'
    ];

    public function order()
    {
        return $this->hasOne(UserOrder::class, 'id', 'order_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function park()
    {
        return $this->belongsTo(Park::class);
    }

    public function scopeDisplay(Builder $query, $display = true)
    {
        return $query->where('is_display', $display);
    }

    public function scopeReviewed(Builder $query)
    {
        return $query->where('audit_status', 2); // 已通过
    }

    public function getCoversAttribute()
    {
        return collect($this->imgurl)->map(function ($item) {
            return $this->getCoverUrl($item);
        })->toArray();
    }

    public function getAuditStatusAttribute($value=1)
    {
        return self::$statusMaps[$value];
    }

    public function scopeSearch(Builder $query, Request $request)
    {
        if (is_numeric($audit_status = $request->input('audit_status'))) {
            $query->where('audit_status', $audit_status);
        }

        if ($project_name = $request->input('project_name')) {
            $query->whereHas('order.parks', function ($query) use ($project_name) {
                $query->where('project_name', 'like', "$project_name");
            });

        }

        if ($car_number = $request->input('car_number')) {
            $query->whereHas('order.car', function ($query) use ($car_number) {
                $query->where('car_number', $car_number);
            });
        }

        if ($nickname = $request->input('nickname')) {
            $query->whereHas('user', function ($query) use ($nickname) {
                $query->where('nickname', 'like', "%$nickname%");
            });
        }

        if ($user_type = $request->input('user_type')) {
            $query->whereHas('user', function ($query) use ($user_type) {
                $query->where('user_type', $user_type);
            });
        }

        if ($comment_start_time = $request->input('comment_start_time')
            && $comment_end_time = $request->input('comment_end_time')) {
            $query->whereDate('created_at', '>', $comment_start_time)
                ->orWhereDate('created_at', '<', $comment_end_time);
        }
    }

    public function block()
    {
        if ($this->audit_status == 3) {
            return;
        }

        $this->update([
            'audit_status' => 3,
            'audit_time' => now(),
            'auditor' => '机器审核',
            'refuse_reason' => '内容违规'
        ]);
    }
}
