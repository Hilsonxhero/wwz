<?php

namespace Modules\User\Http\Middleware;

use Closure;
use App\Services\ApiService;
use Illuminate\Http\Request;

class AdminAuthenticated
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
        if ($request->user()->isSuperUser()) {
            return $next($request);
        }

        return ApiService::_response("دسترسی غیرمجاز کاربر", 401);
    }
}
