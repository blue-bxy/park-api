<?php

namespace App\Models\Users;

use App\Models\EloquentModel;
use App\Models\Parks\Park;
use App\Models\Traits\HasGreen;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserComplaint extends EloquentModel implements RoaGreenInterface
{
    use SoftDeletes, HasGreen;

	protected $fillable = [
        'user_id', 'title', 'content', 'imgurl', 'type', 'result',
        'urgencydegree','order_no','handling_state','handling_person','handling_time',
        'suggestion', 'label', 'response',
        'order_id', 'park_id'
    ];

	protected $casts = [
	    'imgurl' => 'array'
    ];

    public function scopeState(Builder $query, $result)
    {
        return $query->where('result', $result);
	}

    public function getCoversAttribute()
    {
        return collect($this->imgurl)->map(function ($item) {
            return $this->getCoverUrl($item);
        })->toArray();
	}

    public function getResultRenameAttribute()
    {
        return $this->result ? "已完成" : "处理中";
	}

	public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(UserOrder::class);
    }

    public function park()
    {
        return $this->belongsTo(Park::class);
    }

    public function scopeSearch(Builder $query, Request $request)
    {
        if ($project_name = $request->input('project_name')) {
            $query->whereHas('park', function ($query) use ($project_name) {
                $query->where('project_name', 'like', "%$project_name%");
            });
        }

        if ($car_number = $request->input('car_number')) {
            $query->whereHas('user.cars', function ($query) use ($car_number) {
                $query->where('car_number', $car_number);
            });
        }

        if ($request->anyFilled('nickname', 'user_type')) {
            $query->whereHas('user', function ($query) use ($request) {
                if ($nickname = $request->input('nickname')) {
                    $query->where('nickname', 'like', "%$nickname%");
                }

                if ($user_type = $request->input('user_type')) {
                    $query->where('user_type', $user_type);
                }
            });
        }

        if ($complaint_start_time = $request->input('complaint_start_time')) {
            $query->where('created_at', '>=', $complaint_start_time);
        }

        if($complaint_end_time = $request->input('complaint_end_time')){
            $query->where('created_at', '<=', $complaint_end_time);
        }

        if ($handling_state = $request->input('handling_state')) {
            $query->where('handling_state', $handling_state);
        }

    }

    public function block()
    {
        if ($this->result || $this->handling_state == 2) {
            return;
        }

        $this->update([
            'result' => 1,
            'handling_state' => 2,
            'handling_time' => now(),
            'handling_person' => '机器审核'
        ]);
    }
}
