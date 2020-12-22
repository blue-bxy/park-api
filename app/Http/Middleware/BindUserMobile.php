<?php

namespace App\Http\Middleware;

use App\Exceptions\UserMobileNotBindException;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class BindUserMobile
{
    /**
     * handle
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     * @throws UserMobileNotBindException
     */
    public function handle($request, Closure $next)
    {
        if ($request->route()->named('bind.mobile') && $this->need($request)) {
            return $next($request);
        }

        if ($this->need($request)) {
            throw new UserMobileNotBindException();
        }

        return $next($request);
    }

    protected function need(Request $request)
    {
        /** @var User $user */
        $user = $request->user();

        return $user && !$user->mobile;
    }
}
