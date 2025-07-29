<?php

namespace App\Http\Controllers;

use App\Models\MonlamBenchmark;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MonlamBenchmarkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Authentication is handled by middleware
        $benchmarks = MonlamBenchmark::orderBy('created_at', 'desc')->paginate(15);
        return view('benchmark.index', compact('benchmarks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Authorization handled by BenchmarkManagementMiddleware
        
        // Define difficulty levels and question types for the form
        $difficultyLevels = ['easy', 'medium', 'hard', 'expert'];
        $questionTypes = ['mcq' => 'Multiple Choice Question'];
        
        return view('benchmark.create', compact('difficultyLevels', 'questionTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'question_type' => 'required|string|max:50',
            'question_text' => 'required|string',
            'answer_option1' => 'required|string',
            'answer_option2' => 'required|string',
            'answer_option3' => 'required|string',
            'answer_option4' => 'required|string',
            'correct_answer' => 'required|string',
            'explanation' => 'nullable|string',
            'difficulty_level' => 'required|string|max:50',
            'category' => 'nullable|string|max:100',
            'tags' => 'nullable|string|max:255',
            'is_active' => 'nullable|boolean',
        ]);
        
        // Add creator information
        $validated['created_by'] = Auth::user()->name;
        
        // Create benchmark
        $benchmark = MonlamBenchmark::create($validated);
        
        return redirect()->route('benchmark.show', $benchmark->id)
            ->with('success', 'Benchmark question created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Ensure user is authenticated
        if (!Auth::check()) {
            abort(403, 'Unauthorized action.');
        }
        
        $user = Auth::user();
        $benchmark = MonlamBenchmark::findOrFail($id);
        
        // Admin always has access
        if ($user->isAdmin()) {
            return view('benchmark.show', compact('benchmark'));
        }
        
        // Check DB permissions first (highest priority)
        if ($user->hasPermission('all_benchmark', 'view')) {
            return view('benchmark.show', compact('benchmark'));
        }
        
        // Check if user is creator and has own benchmark view permission
        if ($benchmark->created_by === $user->name && $user->hasPermission('own_benchmark', 'view')) {
            return view('benchmark.show', compact('benchmark'));
        }
        
        // Fall back to role-based permissions
        if ($benchmark->created_by === $user->name && $user->canViewOwnBenchmark()) {
            return view('benchmark.show', compact('benchmark'));
        }
        
        if ($user->canViewAllBenchmark()) {
            return view('benchmark.show', compact('benchmark'));
        }
        
        abort(403, 'Unauthorized action. You do not have permission to view this benchmark.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Authorization handled by BenchmarkManagementMiddleware
        
        $benchmark = MonlamBenchmark::findOrFail($id);
        $difficultyLevels = ['easy', 'medium', 'hard', 'expert'];
        $questionTypes = ['mcq' => 'Multiple Choice Question'];
        
        return view('benchmark.edit', compact('benchmark', 'difficultyLevels', 'questionTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'question_type' => 'required|string|max:50',
            'question_text' => 'required|string',
            'answer_option1' => 'required|string',
            'answer_option2' => 'required|string',
            'answer_option3' => 'required|string',
            'answer_option4' => 'required|string',
            'correct_answer' => 'required|string',
            'explanation' => 'nullable|string',
            'difficulty_level' => 'required|string|max:50',
            'category' => 'nullable|string|max:100',
            'tags' => 'nullable|string|max:255',
            'is_active' => 'nullable|boolean',
        ]);
        
        $benchmark = MonlamBenchmark::findOrFail($id);
        
        // Add updater information
        $validated['updated_by'] = Auth::user()->name;
        
        // Update benchmark
        $benchmark->update($validated);
        
        return redirect()->route('benchmark.show', $benchmark->id)
            ->with('success', 'Benchmark question updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        
        $benchmark = MonlamBenchmark::findOrFail($id);
        $benchmark->delete();
        
        return redirect()->route('benchmark.index')
            ->with('success', 'Benchmark question deleted successfully');
    }
}
