<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if ($request->segment(1) == backend_url() && $guard == backend_guard() && Auth::guard(backend_guard())->check()) {
            return redirect()->route(backend_guard(). '.index');
        }

        return $next($request);
    }
}
