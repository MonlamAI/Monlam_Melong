<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserManagementController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        // Auth check is handled in the routes/web.php file
    }
    
    /**
     * Display a listing of the users.
     */
    public function index()
    {
        $users = User::orderBy('name')->get();
        return view('admin.users.index', compact('users'));
    }
    
    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        // Get all unique categories for selection
        $categories = app(MonlamMelongFinetuningController::class)->getUniqueCategories();
        
        return view('admin.users.create', compact('categories'));
    }
    
    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,chief_editor,editor,benchmark_editor,reviewer',
            'allowed_categories' => 'nullable|array',
            'permissions' => 'nullable|array',
        ]);
        
        $user = new User();
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->password = Hash::make($validated['password']);
        $user->role = $validated['role'];
        
        // Handle category permissions based on role
        if (($user->role === 'editor' || $user->role === 'benchmark_editor') && $request->has('allowed_categories')) {
            $user->allowed_categories = $request->allowed_categories;
        } else {
            $user->allowed_categories = null; // Admin, chief editor, and reviewers don't need category restrictions
        }
        
        // Handle detailed permissions matrix
        if ($request->has('permissions')) {
            $user->permissions = $request->permissions;
        } else {
            $user->permissions = null;
        }
        
        $user->save();
        
        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }
    
    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        // Get all unique categories for selection
        $categories = app(MonlamMelongFinetuningController::class)->getUniqueCategories();
        
        return view('admin.users.edit', compact('user', 'categories'));
    }
    
    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,chief_editor,editor,benchmark_editor,reviewer',
            'allowed_categories' => 'nullable|array',
            'permissions' => 'nullable|array',
        ]);
        
        // Update basic information
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role = $validated['role'];
        
        // Update allowed categories for editors, benchmark editors, and reviewers
        if ($user->role === 'editor' || $user->role === 'benchmark_editor' || $user->role === 'reviewer') {
            if ($request->has('allowed_categories')) {
                // Get all available categories
                $allCategories = app(MonlamMelongFinetuningController::class)->getUniqueCategories();
                
                // Check if all categories are selected
                $selectedCategories = $request->allowed_categories;
                $allSelected = count($selectedCategories) === count($allCategories);
                
                if ($allSelected) {
                    // If all categories are selected, save as universal access
                    $user->allowed_categories = ['ཡོངས་རྫོགས།'];
                } else {
                    // Otherwise save the specific categories
                    $user->allowed_categories = $selectedCategories;
                }
            } else {
                $user->allowed_categories = null;
            }
        } else {
            $user->allowed_categories = null; // Admin and chief editor don't need category restrictions
        }
        
        // Update detailed permissions matrix
        if ($request->has('permissions')) {
            $user->permissions = $request->permissions;
        } else {
            $user->permissions = null;
        }
        
        // Update password if provided
        if ($request->filled('password')) {
            $request->validate([
                'password' => 'required|string|min:8|confirmed',
            ]);
            
            $user->password = Hash::make($request->password);
        }
        
        $user->save();
        
        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }
}
