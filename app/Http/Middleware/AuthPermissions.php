<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class AuthPermissions
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard)
    {
        $user = $request->user($guard);

        $route_name = Route::currentRouteName();

        if ($user && !$user->can($route_name)) {

            return $this->sendPermissionFailResponse($request, $guard);
        }

        return $next($request);
    }

    protected function sendPermissionFailResponse(Request $request, $guard)
    {
        return $request->wantsJson() ? response()->json([
            'code'   => 40003,
            'message'    => '您没有权限执行此操作',
            'timestamp' => time()
        ]) : redirect()->intended(route("$guard.login"));
    }
}
