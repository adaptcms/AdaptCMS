<?php

namespace App\Http\Middleware;

use App\Modules\Users\Models\User;

use Closure;
use Auth;

class AuthAdminMiddleware
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
        if (!Auth::check()) {
            return redirect('login');
        }

        $user = User::find(Auth::user()->id);
        if (!$user->role || !$user->role->level > 1) {
            return redirect()->route('home')->with('error', 'Sorry! You do not have access to that page.');
        }

        return $next($request);
    }
}
