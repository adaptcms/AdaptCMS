<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard)
    {
        if (Auth::guest()) {
            return redirect('login');
        }

        if (!$request->user()->isAllowed()) {
           abort(403, 'Sorry! You do not have access to that page.');
        }

        return $next($request);
    }
}
