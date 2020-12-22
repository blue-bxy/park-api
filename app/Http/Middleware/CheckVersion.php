<?php

namespace App\Http\Middleware;

use App\Exceptions\ApiResponseException;
use Closure;

class CheckVersion
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param Closure $next
     * @return mixed
     * @throws ApiResponseException
     */
    public function handle($request, Closure $next)
    {
        // 排除开发环境
        if (app()->isLocal()) {
            return $next($request);
        }

        $version = $request->header('version');
        $platform = $request->header('platform');

        if (!$platform) {
            throw new ApiResponseException("未发行的平台，请通过正规渠道下载", 30010);
        }

        if (!$version) {
            throw new ApiResponseException("版本号未知，请通过正规渠道下载", 30011);
        }

        $platform_version = get_app_version($platform);

        if ($platform_version['is_force'] && $version < $platform_version['version']) {
            throw new ApiResponseException('客户端版本需要升级', 30012);
        }

        return $next($request);
    }
}
