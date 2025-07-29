<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryAccessMiddleware
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
        $user = Auth::user();
        
        // Admin and Chief Editor users can access everything
        if ($user->isAdmin() || $user->isChiefEditor()) {
            return $next($request);
        }
        
        // Get the category from the request if available
        $category = $request->category ?? $request->route('category');
        
        // If no category specified, continue
        if (empty($category)) {
            return $next($request);
        }
        
        // If user cannot access this category, abort
        if (!$user->canAccessCategory($category)) {
            abort(403, 'You do not have permission to access or modify this category.');
        }
        
        return $next($request);
    }
}
