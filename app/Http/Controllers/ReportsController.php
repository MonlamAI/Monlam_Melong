<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Department;

class ReportsController extends Controller
{
    public function user(Request $request)
    {
        $user = Auth::user();
        $period = $request->get('period', 'daily'); // hourly|daily|weekly|monthly|yearly
        [$start, $end] = $this->resolveDateRange($request);

        $active = $this->aggregateActiveMinutes($user->id, $period, $start, $end);
        $words = $this->aggregateWords($user->id, $period, $start, $end);

        return view('reports.user', [
            'period' => $period,
            'start' => $start,
            'end' => $end,
            'active' => $active,
            'words' => $words,
        ]);
    }

    public function admin(Request $request)
    {
        $this->authorizeAdmin();
        $period = $request->get('period', 'daily');
        [$start, $end] = $this->resolveDateRange($request);
        $departmentId = $request->get('department_id');
        $category = $request->get('category');
        $status = $request->get('status');
        $authorId = $request->get('author');

        $departments = Department::orderBy('name')->get();
        $categories = \App\Models\Category::orderBy('name')->get();
        
        $usersQuery = User::query();
        if ($departmentId) {
            $usersQuery->where('department_id', $departmentId);
        }
        $users = $usersQuery->get();
        
        // Authors list (contributors with entries)
        $authors = \App\Models\User::whereHas('entries')
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        // Get entries data using the same logic as the entries page
        $entriesQuery = \App\Models\MonlamMelongFinetuning::with('user')
            ->when($category, function($query) use ($category) {
                return $query->where('category', $category);
            })
            ->when($status, function($query) use ($status) {
                return $query->where('status', $status);
            })
            ->when($authorId, function($query) use ($authorId) {
                return $query->where('user_id', $authorId);
            })
            ->whereBetween('created_at', [$start, $end]);

        $entries = $entriesQuery->get();

        // Generate reports based on entries data
        $reports = $this->generateReportsFromEntries($entries, $period, $start, $end);

        // Compute per-user word totals from activity logs within filters
        $userWordCounts = DB::table('entry_activity_logs as log')
            ->join('users as u', 'u.id', '=', 'log.user_id')
            ->when($departmentId, function($q) use ($departmentId) {
                $q->where('u.department_id', $departmentId);
            })
            ->when($authorId, function($q) use ($authorId) {
                $q->where('log.user_id', $authorId);
            })
            ->when($category, function($q) use ($category) {
                $q->where('log.category', $category);
            })
            ->whereBetween('log.occurred_at', [$start, $end])
            ->groupBy('log.user_id')
            ->select('log.user_id', DB::raw('COALESCE(SUM(log.words_created + log.words_edited), 0) as total_words'))
            ->pluck('total_words', 'log.user_id');

        return view('reports.admin', [
            'period' => $period,
            'start' => $start,
            'end' => $end,
            'departments' => $departments,
            'categories' => $categories,
            'selectedCategory' => $category,
            'selectedStatus' => $status,
            'authors' => $authors,
            'selectedAuthor' => $authorId,
            'departmentId' => $departmentId,
            'users' => $users,
            'reports' => $reports,
            'totalEntries' => $entries->count(),
            'userWordCounts' => $userWordCounts,
        ]);
    }

    private function resolveDateRange(Request $request): array
    {
        $start = $request->get('start') ? \Carbon\Carbon::parse($request->get('start')) : now()->subDays(7);
        $end = $request->get('end') ? \Carbon\Carbon::parse($request->get('end')) : now();
        return [$start, $end];
    }

    private function periodFormat(string $period): array
    {
        // Return format string and group by clause for SQLite
        return match ($period) {
            'hourly' => [
                'format' => '%Y-%m-%d %H:00:00',
                'group' => "strftime('%Y-%m-%d %H:00:00', occurred_at)",
                'interval' => '1 hour'
            ],
            'daily' => [
                'format' => '%Y-%m-%d',
                'group' => "date(occurred_at)",
                'interval' => '1 day'
            ],
            'weekly' => [
                'format' => '%Y-W%W',
                'group' => "strftime('%Y-W%W', occurred_at)",
                'interval' => '1 week'
            ],
            'monthly' => [
                'format' => '%Y-%m',
                'group' => "strftime('%Y-%m', occurred_at)",
                'interval' => '1 month'
            ],
            'yearly' => [
                'format' => '%Y',
                'group' => "strftime('%Y', occurred_at)",
                'interval' => '1 year'
            ],
            default => [
                'format' => '%Y-%m-%d',
                'group' => "date(occurred_at)",
                'interval' => '1 day'
            ],
        };
    }

    private function aggregateActiveMinutes(int $userId, string $period, $start, $end)
    {
        $format = $this->periodFormat($period);
        
        return DB::table('user_heartbeats')
            ->select(
                DB::raw("{$format['group']} as bucket"),
                DB::raw('COUNT(*) as heartbeats')
            )
            ->where('user_id', $userId)
            ->whereBetween('occurred_at', [$start, $end])
            ->groupBy('bucket')
            ->orderBy('bucket')
            ->get();
    }

    private function aggregateWords(int $userId, string $period, $start, $end)
    {
        $format = $this->periodFormat($period);
        
        return DB::table('entry_activity_logs')
            ->select(
                DB::raw("{$format['group']} as bucket"),
                DB::raw('COALESCE(SUM(words_created), 0) as words_created'),
                DB::raw('COALESCE(SUM(words_edited), 0) as words_edited')
            )
            ->where('user_id', $userId)
            ->whereBetween('occurred_at', [$start, $end])
            ->groupBy('bucket')
            ->orderBy('bucket')
            ->get();
    }

    private function aggregateByDepartment(string $period, $start, $end, $departmentId = null, $category = null)
    {
        $format = $this->periodFormat($period);
        
        // For SQLite, we need to handle the date formatting differently
        $dateFormat = str_replace(
            ['%Y', '%m', '%d', '%H', '%i', '%s', '%W'],
            ['%Y', '%m', '%d', '%H', '%M', '%S', '%W'],
            $format['format']
        );
        
        // Get heartbeats data
        $heartbeatsQuery = DB::table('user_heartbeats as hb')
            ->join('users as u', 'u.id', '=', 'hb.user_id')
            ->join('departments as d', 'd.id', '=', 'u.department_id')
            ->select(
                'd.id as department_id',
                'd.name as department_name',
                DB::raw("strftime('{$dateFormat}', hb.occurred_at) as bucket"),
                DB::raw('COUNT(hb.id) as heartbeats')
            )
            ->whereBetween('hb.occurred_at', [$start, $end])
            ->when($departmentId, function($q) use ($departmentId) { 
                $q->where('u.department_id', $departmentId); 
            })
            ->groupBy('d.id', 'd.name', 'bucket')
            ->orderBy('d.name')
            ->orderBy('bucket');

        // Get activity logs data
        $activityQuery = DB::table('entry_activity_logs as log')
            ->join('users as u', 'u.id', '=', 'log.user_id')
            ->join('departments as d', 'd.id', '=', 'u.department_id')
            ->select(
                'd.id as department_id',
                'd.name as department_name',
                DB::raw("strftime('{$dateFormat}', log.occurred_at) as bucket"),
                DB::raw('COALESCE(SUM(log.words_created), 0) as words_created'),
                DB::raw('COALESCE(SUM(log.words_edited), 0) as words_edited'),
                'log.category as category'
            )
            ->whereBetween('log.occurred_at', [$start, $end])
            ->when($departmentId, function($q) use ($departmentId) { 
                $q->where('u.department_id', $departmentId); 
            })
            ->when($category, function($q) use ($category) {
                $q->where('log.category', $category);
            })
            ->groupBy('d.id', 'd.name', 'bucket', 'log.category')
            ->orderBy('d.name')
            ->orderBy('bucket');

        $heartbeats = $heartbeatsQuery->get();
        $activities = $activityQuery->get();

        // Combine the results
        $results = collect();
        
        // Add heartbeats data
        foreach ($heartbeats as $hb) {
            $key = $hb->department_id . '_' . $hb->bucket;
            $results->put($key, [
                'department_id' => $hb->department_id,
                'department_name' => $hb->department_name,
                'bucket' => $hb->bucket,
                'heartbeats' => $hb->heartbeats,
                'words_created' => 0,
                'words_edited' => 0,
                'category' => null
            ]);
        }
        
        // Add activities data
        foreach ($activities as $activity) {
            $key = $activity->department_id . '_' . $activity->bucket . '_' . $activity->category;
            if ($results->has($key)) {
                $results[$key]['words_created'] += $activity->words_created;
                $results[$key]['words_edited'] += $activity->words_edited;
                $results[$key]['category'] = $activity->category;
            } else {
                $results->put($key, [
                    'department_id' => $activity->department_id,
                    'department_name' => $activity->department_name,
                    'bucket' => $activity->bucket,
                    'heartbeats' => 0,
                    'words_created' => $activity->words_created,
                    'words_edited' => $activity->words_edited,
                    'category' => $activity->category
                ]);
            }
        }

        return $results->values();
    }

    private function generateReportsFromEntries($entries, $period, $start, $end)
    {
        $format = $this->periodFormat($period);
        
        // Group entries by category and time period
        $categoryStats = $entries->groupBy(function($entry) use ($format) {
            return $entry->category;
        })->map(function($categoryEntries) use ($format) {
            return $categoryEntries->groupBy(function($entry) use ($format) {
                return $entry->created_at->format($format['format']);
            })->map(function($periodEntries) {
                return [
                    'count' => $periodEntries->count(),
                    'by_status' => $periodEntries->groupBy('status')->map->count(),
                    'by_difficulty' => $periodEntries->groupBy('difficulty')->map->count(),
                    'by_user' => $periodEntries->groupBy('user_id')->map->count(),
                ];
            });
        });

        // Group entries by status
        $statusStats = $entries->groupBy('status')->map(function($statusEntries) use ($format) {
            return $statusEntries->groupBy(function($entry) use ($format) {
                return $entry->created_at->format($format['format']);
            })->map->count();
        });

        // Group entries by user
        $userStats = $entries->groupBy('user_id')->map(function($userEntries) use ($format) {
            return [
                'user' => $userEntries->first()->user,
                'total_entries' => $userEntries->count(),
                'by_category' => $userEntries->groupBy('category')->map->count(),
                'by_status' => $userEntries->groupBy('status')->map->count(),
                'by_period' => $userEntries->groupBy(function($entry) use ($format) {
                    return $entry->created_at->format($format['format']);
                })->map->count(),
            ];
        });

        // Generate time series data
        $timeSeries = [];
        $current = $start->copy();
        while ($current->lte($end)) {
            $bucket = $current->format($format['format']);
            $timeSeries[$bucket] = [
                'date' => $current->copy(),
                'total_entries' => 0,
                'by_category' => [],
                'by_status' => [],
            ];
            $current->add($format['interval']);
        }

        // Populate time series data
        foreach ($entries as $entry) {
            $bucket = $entry->created_at->format($format['format']);
            if (isset($timeSeries[$bucket])) {
                $timeSeries[$bucket]['total_entries']++;
                $timeSeries[$bucket]['by_category'][$entry->category] = 
                    ($timeSeries[$bucket]['by_category'][$entry->category] ?? 0) + 1;
                $timeSeries[$bucket]['by_status'][$entry->status] = 
                    ($timeSeries[$bucket]['by_status'][$entry->status] ?? 0) + 1;
            }
        }

        return [
            'category_stats' => $categoryStats,
            'status_stats' => $statusStats,
            'user_stats' => $userStats,
            'time_series' => collect($timeSeries)->values(),
            'summary' => [
                'total_entries' => $entries->count(),
                'categories_count' => $entries->pluck('category')->unique()->count(),
                'statuses_count' => $entries->pluck('status')->unique()->count(),
                'users_count' => $entries->pluck('user_id')->unique()->count(),
                'avg_difficulty' => $entries->avg('difficulty'),
            ]
        ];
    }

    private function authorizeAdmin(): void
    {
        $user = Auth::user();
        if (!$user || !$user->isAdmin()) {
            abort(403, 'Unauthorized');
        }
    }
}
