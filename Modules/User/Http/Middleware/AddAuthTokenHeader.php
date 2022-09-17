<?php

namespace Modules\User\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AddAuthTokenHeader
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

        // $cookie_name = env('AUTH_COOKIE_NAME');
        $cookie_name = 'access_token';

        if (!$request->bearerToken()) {
            if ($request->hasCookie($cookie_name)) {
                $token = $request->cookie($cookie_name);
                $request->headers->add([
                    'Authorization' => 'Bearer ' . $token
                ]);
            }
        }

        return $next($request);
    }
}
