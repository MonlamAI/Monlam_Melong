<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BenchmarkManagementMiddleware
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
        if (!Auth::check()) {
            abort(403, 'User must be authenticated to access benchmark management.');
        }
        
        $user = Auth::user();
        $action = $this->determineAction($request);
        $benchmarkId = $request->route('monlamBenchmark');

        // Admin always has full access to all features
        if ($user->isAdmin()) {
            return $next($request);
        }
        
        // Check specific permissions from database first (highest priority)
        // For viewing/editing/deleting specific benchmark
        if ($benchmarkId) {
            $benchmark = \App\Models\MonlamBenchmark::find($benchmarkId);
            if (!$benchmark) {
                abort(404, 'Benchmark question not found.');
            }
            
            // Check database permissions for "all_benchmark" feature
            if ($action === 'view' && $user->hasPermission('all_benchmark', 'view')) {
                return $next($request);
            } elseif ($action === 'edit' && $user->hasPermission('all_benchmark', 'edit')) {
                return $next($request);
            } elseif ($action === 'delete' && $user->hasPermission('all_benchmark', 'delete')) {
                return $next($request);
            }
            
            // Check if user is the creator and has own benchmark permissions
            if ($benchmark->created_by === $user->name) {
                // Check database permissions for "own_benchmark" feature first
                if ($action === 'view' && $user->hasPermission('own_benchmark', 'view')) {
                    return $next($request);
                } elseif ($action === 'edit' && $user->hasPermission('own_benchmark', 'edit')) {
                    return $next($request);
                } elseif ($action === 'delete' && $user->hasPermission('own_benchmark', 'delete')) {
                    return $next($request);
                }
                
                // Fall back to role-based permissions
                if ($action === 'view' && $user->canViewOwnBenchmark()) {
                    return $next($request);
                } elseif ($action === 'edit' && $user->canEditOwnBenchmark()) {
                    return $next($request);
                } elseif ($action === 'delete' && $user->canDeleteOwnBenchmark()) {
                    return $next($request);
                }
            }
            
            // If not the creator, check if user has permissions for all benchmarks
            // First check database permissions
            if ($action === 'view' && $user->hasPermission('all_benchmark', 'view')) {
                return $next($request);
            } elseif ($action === 'edit' && $user->hasPermission('all_benchmark', 'edit')) {
                return $next($request);
            } elseif ($action === 'delete' && $user->hasPermission('all_benchmark', 'delete')) {
                return $next($request);
            }
            
            // Then fall back to role-based permissions
            if ($action === 'view' && $user->canViewAllBenchmark()) {
                return $next($request);
            } elseif ($action === 'edit' && $user->canEditAllBenchmark()) {
                return $next($request);
            } elseif ($action === 'delete' && $user->canDeleteAllBenchmark()) {
                return $next($request);
            }
        } 
        // For general management actions like listing or creating new
        else {
            // Check database permissions first
            if ($action === 'create' && $user->hasPermission('all_benchmark', 'create')) {
                return $next($request);
            } elseif ($action === 'view' && $user->hasPermission('all_benchmark', 'view')) {
                return $next($request);
            }
            
            // Fall back to role-based permissions
            if ($action === 'create' && ($user->canCreateOwnBenchmark() || $user->canCreateAllBenchmark())) {
                return $next($request);
            } elseif ($action === 'view' && ($user->canViewOwnBenchmark() || $user->canViewAllBenchmark())) {
                return $next($request);
            }
        }
        
        // Legacy check for backward compatibility (lowest priority)
        if ($user->canManageBenchmark($action)) {
            return $next($request);
        }
        
        abort(403, 'Unauthorized action. You do not have the required benchmark permissions.');
    }
    
    /**
     * Determine the action based on the request method and route
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    private function determineAction(Request $request)
    {
        $method = $request->method();
        $route = $request->route()->getName();
        
        if ($method === 'GET') {
            if (strpos($route, 'edit') !== false) {
                return 'edit';
            }
            if (strpos($route, 'create') !== false) {
                return 'create';
            }
            return 'view';
        }
        
        if ($method === 'POST') {
            return 'create';
        }
        
        if ($method === 'PUT' || $method === 'PATCH') {
            return 'edit';
        }
        
        if ($method === 'DELETE') {
            return 'delete';
        }
        
        return 'view'; // Default fallback
    }
}
