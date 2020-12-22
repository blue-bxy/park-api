<?php

namespace App\Http\Controllers\Property;

use App\Http\Controllers\App\BaseController;
use App\Http\Resources\ActivityLogResource;
use App\Models\ActivityLog;
use App\Models\Admin;
use App\Models\Property;
use Illuminate\Http\Request;

class ActivityLogController extends BaseController
{

    public function index(Request $request)
    {
        $perPage = $request->input('per_page');

        $park_id = ($request->user())->park_id;

        $query = ActivityLog::query();

        // 给个默认的查询时间为近31天
        $start_time = date('Y-m-d H:i:s',strtotime("-31 day"));

        if ($time = $request->input('start_time')) {
            $query->where('created_at','>=',$time);
        }else{
            $query->where('created_at','>=',$start_time);
        }

        if ($end_time = $request->input('end_time')) {
            $query->where('created_at','<=',$end_time);
        }

        if ($user_name = $request->input('user_name')) {
            $query->whereHasMorph('causer', 'App\Models\Property', function ($query) use ($user_name) {
                $query->where("name", 'like', "%{$user_name}%");
            });
        }

        $query->with('causer');

        $query->whereHasMorph('causer',  'App\Models\Property', function ($query) use ($park_id) {
            $query->where("park_id",$park_id);
        });

        $activity_log = $query->latest()->paginate($perPage);

        return ActivityLogResource::collection($activity_log);
    }
}
