<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\MonlamMelongFinetuning;
use App\Models\Category;
use App\Models\Tag;
use App\Models\MonlamBenchmark;

class DashboardController extends Controller
{
    /**
     * Display the dashboard based on user's permission matrix
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get user's permissions from the permission matrix
        $permissions = $user->permissions ?? [];
        
        // Determine what content to show based on permissions
        $showEntries = $this->shouldShowEntries($user, $permissions);
        $showCategories = $this->shouldShowCategories($user, $permissions);
        $showBenchmarks = $this->shouldShowBenchmarks($user, $permissions);
        $showTags = $this->shouldShowTags($user, $permissions);
        $showReviewQueue = $this->shouldShowReviewQueue($user, $permissions);
        $showUserManagement = $this->shouldShowUserManagement($user, $permissions);
        
        // Recompute overall stats visibility from individual flags
        $showStats = (
            $showEntries ||
            $showCategories ||
            $showBenchmarks ||
            $showTags ||
            $showReviewQueue ||
            $showUserManagement
        );
        
        // Get data based on permissions
        $dashboardData = $this->getDashboardData($user, $permissions, [
            'showStats' => $showStats,
            'showEntries' => $showEntries,
            'showCategories' => $showCategories,
            'showBenchmarks' => $showBenchmarks,
            'showTags' => $showTags,
            'showReviewQueue' => $showReviewQueue,
            'showUserManagement' => $showUserManagement,
        ]);
        
        // Combine show flags with dashboard data
        $data = array_merge($dashboardData, [
            'showStats' => $showStats,
            'showEntries' => $showEntries,
            'showCategories' => $showCategories,
            'showBenchmarks' => $showBenchmarks,
            'showTags' => $showTags,
            'showReviewQueue' => $showReviewQueue,
            'showUserManagement' => $showUserManagement,
        ]);
        
        return view('dashboard', compact('data'));
    }
    
    /**
     * Determine if user should see statistics
     */
    private function shouldShowStats($user, $permissions)
    {
        // Admin and Chief Editor always see stats
        if ($user->isAdmin() || $user->isChiefEditor()) {
            return true;
        }
        
        // Check permission matrix for stats access
        if (isset($permissions['stats']['view']) && $permissions['stats']['view']) {
            return true;
        }
        
        // If user has any content management permissions, show stats
        return $this->hasAnyContentPermission($user, $permissions);
    }
    
    /**
     * Determine if user should see entries
     */
    private function shouldShowEntries($user, $permissions)
    {
        // Admin, Chief Editor, Reviewer always see entries
        if ($user->isAdmin() || $user->isChiefEditor() || $user->isReviewer()) {
            return true;
        }
        
        // For other roles, require explicit permission matrix
        $hasEntriesView = isset($permissions['entries']['view']) && $permissions['entries']['view'];
        $hasOwnSubmittedView = isset($permissions['own_submitted']['view']) && $permissions['own_submitted']['view'];
        $hasAllSubmittedView = isset($permissions['all_submitted']['view']) && $permissions['all_submitted']['view'];
        
        if ($hasEntriesView || $hasOwnSubmittedView || $hasAllSubmittedView) {
            return true;
        }
        
        return false;
    }
    
    /**
     * Determine if user should see categories
     */
    private function shouldShowCategories($user, $permissions)
    {
        // Admin and Chief Editor always see categories
        if ($user->isAdmin() || $user->isChiefEditor()) {
            return true;
        }
        
        // Check permission matrix
        if (isset($permissions['categories']['view']) && $permissions['categories']['view']) {
            return true;
        }
        
        return false;
    }
    
    /**
     * Determine if user should see benchmarks
     */
    private function shouldShowBenchmarks($user, $permissions)
    {
        // Use the existing canManageBenchmarks method
        return $user->canManageBenchmarks();
    }
    
    /**
     * Determine if user should see tags
     */
    private function shouldShowTags($user, $permissions)
    {
        // Use the existing canManageTags method
        return $user->canManageTags();
    }
    
    /**
     * Determine if user should see review queue
     */
    private function shouldShowReviewQueue($user, $permissions)
    {
        // Admin, Chief Editor, Reviewer always see review queue
        if ($user->isAdmin() || $user->isChiefEditor() || $user->isReviewer()) {
            return true;
        }
        
        // Check permission matrix
        if (isset($permissions['review']['view']) && $permissions['review']['view']) {
            return true;
        }
        
        return false;
    }
    
    /**
     * Determine if user should see user management
     */
    private function shouldShowUserManagement($user, $permissions)
    {
        // Only Admin can see user management
        if ($user->isAdmin()) {
            return true;
        }
        
        // Check permission matrix
        if (isset($permissions['users']['view']) && $permissions['users']['view']) {
            return true;
        }
        
        return false;
    }
    
    /**
     * Check if user has any content management permissions
     */
    private function hasAnyContentPermission($user, $permissions)
    {
        $contentPermissions = ['entries', 'categories', 'tags', 'benchmarks'];
        
        foreach ($contentPermissions as $permission) {
            if (isset($permissions[$permission]['view']) && $permissions[$permission]['view']) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Check if user has entry permissions
     */
    private function hasEntryPermission($user, $permissions)
    {
        // Check permission matrix first
        if (isset($permissions['entries']['view']) && $permissions['entries']['view']) {
            return true;
        }
        
        // For Editor role, require explicit permission matrix setting
        if ($user->isEditor()) {
            return false;
        }
        
        return false;
    }
    
    /**
     * Get dashboard data based on permissions
     */
    private function getDashboardData($user, $permissions, $showFlags)
    {
        $data = [
            'totalEntries' => 0,
            'draftEntries' => 0,
            'pendingEntries' => 0,
            'approvedEntries' => 0,
            'rejectedEntries' => 0,
            'totalCategories' => 0,
            'totalBenchmarks' => 0,
            'totalTags' => 0,
            'userEntries' => 0,
        ];
        
        // Get entries data if user can see entries
        if ($showFlags['showEntries']) {
            $entriesQuery = MonlamMelongFinetuning::query();
            
            // If user is editor, only show their entries or entries in their allowed categories
            if ($user->isEditor() && !$user->isAdmin() && !$user->isChiefEditor()) {
                if (!empty($user->allowed_categories) && !in_array('ཡོངས་རྫོགས།', $user->allowed_categories)) {
                    $entriesQuery->whereIn('category', $user->allowed_categories);
                } else {
                    $entriesQuery->where('user_id', $user->id);
                }
            }
            
            $data['totalEntries'] = $entriesQuery->count();
            $data['draftEntries'] = $entriesQuery->where('status', 'draft')->count();
            $data['pendingEntries'] = $entriesQuery->where('status', 'pending')->count();
            $data['approvedEntries'] = $entriesQuery->where('status', 'approved')->count();
            $data['rejectedEntries'] = $entriesQuery->where('status', 'rejected')->count();
            
            // User's own entries
            $data['userEntries'] = MonlamMelongFinetuning::where('user_id', $user->id)->count();
        }
        
        // Get categories data if user can see categories
        if ($showFlags['showCategories']) {
            $data['totalCategories'] = MonlamMelongFinetuning::distinct('category')
                ->whereNotNull('category')
                ->count('category');
        }
        
        // Get benchmarks data if user can see benchmarks
        if ($showFlags['showBenchmarks']) {
            $benchmarksQuery = MonlamBenchmark::query();
            
            // If user is not admin/chief editor, only show their own benchmarks
            if (!$user->isAdmin() && !$user->isChiefEditor()) {
                $benchmarksQuery->where('created_by', $user->name);
            }
            
            $data['totalBenchmarks'] = $benchmarksQuery->count();
        }
        
        // Get tags data if user can see tags
        if ($showFlags['showTags']) {
            $data['totalTags'] = Tag::count();
        }
        
        return $data;
    }
}
