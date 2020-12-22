<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserLoginLog
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $response =  $next($request);
        $user = $request->user();
        DB::transaction(function () use ($request, $user) {
            $log = \App\Models\Users\UserLoginLog::query()->whereDate('created_at', '=', date('Y-m-d'))
                ->firstOrNew(['user_id' => $user->id]);
            $log->last_ip = $request->ip();
            $log->updated_at = date('Y-m-d H:i:s');
            $log->save();
        });
        return $response;
    }
}
