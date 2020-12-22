<?php

namespace App\Http\Middleware;

use Closure;

class DisableLogging
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // 关闭自动写入
        activity()->disableLogging();

        return $next($request);
    }
}
