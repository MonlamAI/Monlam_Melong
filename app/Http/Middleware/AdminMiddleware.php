<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
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
        // Allow both Admin and Chief Editor to access admin routes
        if (!Auth::check() || (!Auth::user()->isAdmin() && !Auth::user()->isChiefEditor())) {
            abort(403, 'Unauthorized action. Admin access required.');
        }

        return $next($request);
    }
}
