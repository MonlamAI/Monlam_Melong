<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cache;
use App\Models\Department;
use App\Models\UserHeartbeat;
use App\Models\EntryActivityLog;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'department_id',
        'password',
        'role',
        'allowed_categories',
        'permissions',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'allowed_categories' => 'array',
            'permissions' => 'array',
        ];
    }

    /**
     * Get the allowed_categories attribute with proper encoding.
     *
     * @param mixed $value
     * @return array|null
     */
    public function getAllowedCategoriesAttribute($value)
    {
        if (empty($value)) {
            return null;
        }

        // If it's already an array, return as is
        if (is_array($value)) {
            return $value;
        }

        // Decode with JSON_UNESCAPED_UNICODE to preserve Tibetan characters
        $decoded = json_decode($value, true, 512, JSON_UNESCAPED_UNICODE);
        return $decoded;
    }

    /**
     * Set the allowed_categories attribute with proper encoding.
     *
     * @param mixed $value
     * @return void
     */
    public function setAllowedCategoriesAttribute($value)
    {
        if (empty($value)) {
            $this->attributes['allowed_categories'] = null;
            return;
        }

        // Store as JSON with JSON_UNESCAPED_UNICODE to preserve Tibetan characters
        $this->attributes['allowed_categories'] = json_encode($value, JSON_UNESCAPED_UNICODE);
    }

    /**
     * Get the entries that belong to this user.
     */
    public function entries()
    {
        return $this->hasMany(MonlamMelongFinetuning::class);
    }

    /**
     * Department relationship.
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Heartbeats relationship.
     */
    public function heartbeats()
    {
        return $this->hasMany(UserHeartbeat::class);
    }

    /**
     * Entry activity logs relationship.
     */
    public function entryActivityLogs()
    {
        return $this->hasMany(EntryActivityLog::class);
    }

    /**
     * Check if user is admin.
     * Admin has full permissions for the system
     *
     * @return bool
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is chief editor.
     * Chief editor has full editing permissions but no staff management
     *
     * @return bool
     */
    public function isChiefEditor()
    {
        return $this->role === 'chief_editor';
    }

    /**
     * Check if user is editor.
     * Editor can create and edit own content, but cannot submit for review
     *
     * @return bool
     */
    public function isEditor()
    {
        return $this->role === 'editor';
    }

    /**
     * Check if user is allowed to access a specific category.
     *
     * @param string|null $category
     * @return bool
     */
    public function canAccessCategory($category)
    {
        // If no category provided, deny access
        if (empty($category)) {
            return false;
        }

        // If user has no category restrictions, allow access
        if (empty($this->allowed_categories)) {
            return true;
        }

        // Check if the category is in allowed categories
        return in_array($category, $this->allowed_categories);
    }

    /**
     * Get the allowed categories for the user.
     *
     * @return array
     */
    public function getAllowedCategories()
    {
        return $this->allowed_categories ?? [];
    }

    /**
     * Set the allowed categories for the user.
     *
     * @param array $categories
     * @return $this
     */
    public function setAllowedCategories(array $categories)
    {
        $this->allowed_categories = $categories;
        return $this;
    }

    /**
     * Check if user is benchmark editor.
     * Benchmark editor can create, edit, and delete benchmark questions
     *
     * @return bool
     */
    public function isBenchmarkEditor()
    {
        return $this->role === 'benchmark_editor';
    }

    /**
     * Check if user is reviewer.
     * Reviewer can review and edit all content, but has no staff management permissions
     *
     * @return bool
     */
    public function isReviewer()
    {
        return $this->role === 'reviewer';
    }

    /**
     * Check if user has benchmark management permissions
     * Now with caching for improved performance
     *
     * @return bool
     */
    public function canManageBenchmarks()
    {
        // Create a unique cache key based on user ID
        $cacheKey = "user_{$this->id}_can_manage_benchmarks";
        
        // Cache the result for 5 minutes
        return Cache::remember($cacheKey, 300, function () {
            // Check role-based permissions
            if ($this->isAdmin() || $this->isBenchmarkEditor() || $this->isChiefEditor() || $this->isEditor()) {
                return true;
            }
            
            // Check if specific permission is set in the permissions matrix
            if (isset($this->permissions['benchmark'])) {
                // Check if user has any CRUD permission for benchmark
                if (isset($this->permissions['benchmark']['create']) && $this->permissions['benchmark']['create'] ||
                    isset($this->permissions['benchmark']['edit']) && $this->permissions['benchmark']['edit'] ||
                    isset($this->permissions['benchmark']['delete']) && $this->permissions['benchmark']['delete'] ||
                    isset($this->permissions['benchmark']['view']) && $this->permissions['benchmark']['view']) {
                    return true;
                }
            }
            
            return false;
        });
    }

    /**
     * Check if user can submit content for review
     *
     * @return bool
     */
    public function canSubmitForReview()
    {
        return $this->isAdmin() || $this->isChiefEditor();
    }

    /**
     * Check if user can review content
     *
     * @return bool
     */
    public function canReviewContent()
    {
        return $this->isAdmin() || $this->isReviewer() || $this->isChiefEditor();
    }

    /**
     * Check if user can manage staff/users
     *
     * @return bool
     */
    public function canManageStaff()
    {
        return $this->isAdmin(); // Only admin can manage staff
    }

    /**
     * Check if user can manage categories
     * Now with caching for improved performance
     *
     * @return bool
     */
    public function canManageCategories()
    {
        $cacheKey = "user_{$this->id}_can_manage_categories";
        
        return Cache::remember($cacheKey, 300, function () {
            return $this->isAdmin() || $this->isChiefEditor();
        });
    }

    /**
     * Check if user can manage Tags
     * Now with caching for improved performance
     *
     * @return bool
     */
    public function canManageTags()
    {
        // Create a unique cache key based on user ID
        $cacheKey = "user_{$this->id}_can_manage_tags";
        
        // Cache the result for 5 minutes
        return Cache::remember($cacheKey, 300, function () {
            // Check role-based permissions
            if ($this->isAdmin() || $this->isChiefEditor()) {
                return true;
            }
            
            // Check if specific permission is set in the permissions matrix
            if (isset($this->permissions['Tags'])) {
                // Check if user has any CRUD permission for tags
                if (isset($this->permissions['Tags']['create']) && $this->permissions['Tags']['create'] ||
                    isset($this->permissions['Tags']['edit']) && $this->permissions['Tags']['edit'] ||
                    isset($this->permissions['Tags']['delete']) && $this->permissions['Tags']['delete'] ||
                    isset($this->permissions['Tags']['view']) && $this->permissions['Tags']['view']) {
                    return true;
                }
            }
            
            return false;
        });
    }

    /**
     * Check if user has a specific permission based on the new permissions matrix
     * Now with caching for improved performance
     *
     * @param string $feature The feature to check (review, users, categories, Tags, benchmark)
     * @param string $action The action to check (create, edit, delete, view)
     * @return bool Whether the user has the permission
     */
    public function hasPermission($feature, $action)
    {
        // Create a unique cache key based on user ID, feature and action
        $cacheKey = "user_{$this->id}_permission_{$feature}_{$action}";
        
        // Try to get the permission result from cache (5 minutes TTL)
        return Cache::remember($cacheKey, 300, function () use ($feature, $action) {
            // Admin always has all permissions
            if ($this->isAdmin()) {
                return true;
            }
            
            // First check the permissions matrix if it exists
            if (!empty($this->permissions) && isset($this->permissions[$feature][$action])) {
                return (bool) $this->permissions[$feature][$action];
            }
            
            // Check wildcard permissions if they exist
            if (isset($this->permissions[$feature]['*'])) {
                return (bool) $this->permissions[$feature]['*'];
            }
            
            if (isset($this->permissions['*'][$action])) {
                return (bool) $this->permissions['*'][$action];
            }

            if (isset($this->permissions['*']['*'])) {
                return (bool) $this->permissions['*']['*'];
            }

            // If not found in permissions matrix, check role-based permissions
            return $this->hasRoleBasedPermission($feature, $action);
        });
    }

    /**
     * Check if user has a role-based permission for a specific feature and action
     *
     * @param string $feature The feature to check
     * @param string $action The action to check
     * @return bool Whether the user has the permission based on their role
     */
    protected function hasRoleBasedPermission($feature, $action)
    {
        // Chief Editor permissions
        if ($this->isChiefEditor()) {
            // Chief Editor can do everything except manage users
            if ($feature === 'users') {
                return false;
            }
            return true;
        }

        // Reviewer permissions
        if ($this->isReviewer()) {
            // Reviewers can view all content and edit/review content
            if ($feature === 'review' && in_array($action, ['view', 'edit'])) {
                return true;
            }
            return false;
        }

        // Editor permissions
        if ($this->isEditor()) {
            // Editors can create and edit content, but not manage other features
            if ($feature === 'review' && in_array($action, ['create', 'edit', 'view'])) {
                return true;
            }
            return false;
        }

        // Benchmark Editor permissions
        if ($this->isBenchmarkEditor()) {
            // Benchmark editors can manage benchmark content
            if ($feature === 'benchmark') {
                return true;
            }
            return false;
        }

        // Default deny
        return false;
    }

    /**
     * Check if user has permission to view their own submitted entries
     *
     * @return bool
     */
    public function canViewOwnSubmittedEntries()
    {
        // Check if specific permission is set
        if (isset($this->permissions['own_submitted']['view'])) {
            return (bool) $this->permissions['own_submitted']['view'];
        }

        // Default permissions for various roles
        if ($this->isAdmin() || $this->isChiefEditor() || $this->isReviewer() || $this->isEditor()) {
            return true;
        }

        return false;
    }

    /**
     * Check if user has permission to edit their own submitted entries
     *
     * @return bool
     */
    public function canEditOwnSubmittedEntries()
    {
        // Check if specific permission is set
        if (isset($this->permissions['own_submitted']['edit'])) {
            return (bool) $this->permissions['own_submitted']['edit'];
        }

        // Default permissions for various roles
        if ($this->isAdmin() || $this->isChiefEditor() || $this->isReviewer() || $this->isEditor()) {
            return true;
        }

        return false;
    }

    /**
     * Check if user has permission to delete their own submitted entries
     *
     * @return bool
     */
    public function canDeleteOwnSubmittedEntries()
    {
        // Check if specific permission is set
        if (isset($this->permissions['own_submitted']['delete'])) {
            return (bool) $this->permissions['own_submitted']['delete'];
        }

        // Default permissions for various roles
        if ($this->isAdmin() || $this->isChiefEditor()) {
            return true;
        }

        return false;
    }

    /**
     * Check if user has permission to view all submitted entries
     *
     * @return bool
     */
    public function canViewAllSubmittedEntries()
    {
        // Check if specific permission is set
        if (isset($this->permissions['all_submitted']['view'])) {
            return (bool) $this->permissions['all_submitted']['view'];
        }

        // Default permissions for various roles
        if ($this->isAdmin() || $this->isChiefEditor() || $this->isReviewer()) {
            return true;
        }

        return false;
    }

    /**
     * Check if user has permission to edit all submitted entries
     *
     * @return bool
     */
    public function canEditAllSubmittedEntries()
    {
        // Check if specific permission is set
        if (isset($this->permissions['all_submitted']['edit'])) {
            return (bool) $this->permissions['all_submitted']['edit'];
        }

        // Default permissions for various roles
        if ($this->isAdmin() || $this->isChiefEditor() || $this->isReviewer()) {
            return true;
        }

        return false;
    }

    /**
     * Check if user has permission to delete all submitted entries
     *
     * @return bool
     */
    public function canDeleteAllSubmittedEntries()
    {
        // Check if specific permission is set
        if (isset($this->permissions['all_submitted']['delete'])) {
            return (bool) $this->permissions['all_submitted']['delete'];
        }

        // Default permissions for various roles
        if ($this->isAdmin() || $this->isChiefEditor()) {
            return true;
        }

        return false;
    }

    /**
     * Check if user has permission to view their own benchmark questions
     *
     * @return bool
     */
    public function canViewOwnBenchmark()
    {
        // Check if specific permission is set
        if (isset($this->permissions['own_benchmark']['view'])) {
            return (bool) $this->permissions['own_benchmark']['view'];
        }

        // Default permissions for various roles
        if ($this->isAdmin() || $this->isChiefEditor() || $this->isBenchmarkEditor() || $this->isEditor()) {
            return true;
        }

        return false;
    }

    /**
     * Check if user has permission to edit their own benchmark questions
     *
     * @return bool
     */
    public function canEditOwnBenchmark()
    {
        // Check if specific permission is set
        if (isset($this->permissions['own_benchmark']['edit'])) {
            return (bool) $this->permissions['own_benchmark']['edit'];
        }

        // Default permissions for various roles
        if ($this->isAdmin() || $this->isChiefEditor() || $this->isBenchmarkEditor() || $this->isEditor()) {
            return true;
        }

        return false;
    }

    /**
     * Check if user has permission to create their own benchmark questions
     *
     * @return bool
     */
    public function canCreateOwnBenchmark()
    {
        // Check if specific permission is set
        if (isset($this->permissions['own_benchmark']['create'])) {
            return (bool) $this->permissions['own_benchmark']['create'];
        }

        // Default permissions for various roles
        if ($this->isAdmin() || $this->isChiefEditor() || $this->isBenchmarkEditor() || $this->isEditor()) {
            return true;
        }

        return false;
    }

    /**
     * Check if user has permission to delete their own benchmark questions
     *
     * @return bool
     */
    public function canDeleteOwnBenchmark()
    {
        // Check if specific permission is set
        if (isset($this->permissions['own_benchmark']['delete'])) {
            return (bool) $this->permissions['own_benchmark']['delete'];
        }

        // Default permissions for various roles
        if ($this->isAdmin() || $this->isChiefEditor() || $this->isBenchmarkEditor() || $this->isEditor()) {
            return true;
        }

        return false;
    }

    /**
     * Check if user has permission to view all benchmark questions
     *
     * @return bool
     */
    public function canViewAllBenchmark()
    {
        // Check if specific permission is set
        if (isset($this->permissions['all_benchmark']['view'])) {
            return (bool) $this->permissions['all_benchmark']['view'];
        }

        // Default permissions for various roles
        if ($this->isAdmin() || $this->isChiefEditor() || $this->isBenchmarkEditor() || $this->isEditor()) {
            return true;
        }

        return false;
    }

    /**
     * Check if user has permission to edit all benchmark questions
     *
     * @return bool
     */
    public function canEditAllBenchmark()
    {
        // Check if specific permission is set
        if (isset($this->permissions['all_benchmark']['edit'])) {
            return (bool) $this->permissions['all_benchmark']['edit'];
        }

        // Default permissions for various roles
        if ($this->isAdmin() || $this->isChiefEditor() || $this->isBenchmarkEditor() || $this->isEditor()) {
            return true;
        }

        return false;
    }

    /**
     * Check if user has permission to create all benchmark questions
     *
     * @return bool
     */
    public function canCreateAllBenchmark()
    {
        // Check if specific permission is set
        if (isset($this->permissions['all_benchmark']['create'])) {
            return (bool) $this->permissions['all_benchmark']['create'];
        }

        // Default permissions for various roles
        if ($this->isAdmin() || $this->isChiefEditor() || $this->isBenchmarkEditor() || $this->isEditor()) {
            return true;
        }

        return false;
    }

    /**
     * Check if user has permission to delete all benchmark questions
     *
     * @return bool
     */
    public function canDeleteAllBenchmark()
    {
        // Check if specific permission is set
        if (isset($this->permissions['all_benchmark']['delete'])) {
            return (bool) $this->permissions['all_benchmark']['delete'];
        }

        // Default permissions for various roles
        if ($this->isAdmin() || $this->isChiefEditor() || $this->isBenchmarkEditor() || $this->isEditor()) {
            return true;
        }

        return false;
    }

    /**
     * Check if user has benchmark management permissions
     *
     * @param string $action
     * @return bool
     */
    public function canManageBenchmark($action = 'view')
    {
        // Admin, Chief Editor, and Benchmark Editor can manage benchmarks
        if ($this->isAdmin() || $this->isChiefEditor() || $this->isBenchmarkEditor()) {
            return true;
        }

        // Check if user has permission to access all benchmarks
        if ($action === 'view' && $this->canViewAllBenchmark()) {
            return true;
        } else if ($action === 'edit' && $this->canEditAllBenchmark()) {
            return true;
        } else if ($action === 'create' && $this->canCreateAllBenchmark()) {
            return true;
        } else if ($action === 'delete' && $this->canDeleteAllBenchmark()) {
            return true;
        }

        // Check if user has specific benchmark permission (legacy support)
        if (isset($this->permissions['benchmark'][$action])) {
            return (bool) $this->permissions['benchmark'][$action];
        }

        return false;
    }

    /**
     * Check if user can view an entry
     *
     * @param MonlamMelongFinetuning $entry
     * @return bool
     */
    public function canViewEntry($entry)
    {
        // Admin, Chief Editor, and Reviewer can view all entries
        if ($this->isAdmin() || $this->isChiefEditor() || $this->isReviewer() || $this->hasPermission('review', 'view')) {
            return true;
        }

        // Check if user has permission to view all submitted entries
        if ($this->canViewAllSubmittedEntries()) {
            return true;
        }

        // Editor can view their own entries
        if ($this->id === $entry->user_id) {
            // Check if user has permission to view own submitted entries
            if ($this->canViewOwnSubmittedEntries()) {
                return true;
            }
        }

        // Editor can view entries in their allowed categories
        if ($this->isEditor() && $this->canAccessCategory($entry->category)) {
            return true;
        }

        return false;
    }

    /**
     * Check if user can edit an entry
     *
     * @param MonlamMelongFinetuning $entry
     * @return bool
     */
    public function canEditEntry($entry)
    {
        // Admin, Chief Editor, and Reviewer can edit all entries
        if ($this->isAdmin() || $this->isChiefEditor() || $this->isReviewer() || $this->hasPermission('review', 'edit')) {
            return true;
        }

        // Check if user has permission to edit all submitted entries
        if ($this->canEditAllSubmittedEntries()) {
            return true;
        }

        // Editor can edit their own entries
        if ($this->id === $entry->user_id) {
            // Check if user has permission to edit own submitted entries
            if ($this->canEditOwnSubmittedEntries()) {
                return true;
            }
        }

        return false;
    }
}
