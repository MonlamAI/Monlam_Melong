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

        $departments = Department::orderBy('name')->get();
        $categories = \App\Models\Category::orderBy('name')->get();
        
        $usersQuery = User::query();
        if ($departmentId) {
            $usersQuery->where('department_id', $departmentId);
        }
        $users = $usersQuery->get();

        $deptSummaries = $this->aggregateByDepartment($period, $start, $end, $departmentId, $category);

        return view('reports.admin', [
            'period' => $period,
            'start' => $start,
            'end' => $end,
            'departments' => $departments,
            'categories' => $categories,
            'selectedCategory' => $category,
            'departmentId' => $departmentId,
            'users' => $users,
            'deptSummaries' => $deptSummaries,
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
                'group' => "strftime('%Y-%m-%d %H:00:00', occurred_at)"
            ],
            'daily' => [
                'format' => '%Y-%m-%d',
                'group' => "date(occurred_at)"
            ],
            'weekly' => [
                'format' => '%Y-W%W',
                'group' => "strftime('%Y-W%W', occurred_at)"
            ],
            'monthly' => [
                'format' => '%Y-%m',
                'group' => "strftime('%Y-%m', occurred_at)"
            ],
            'yearly' => [
                'format' => '%Y',
                'group' => "strftime('%Y', occurred_at)"
            ],
            default => [
                'format' => '%Y-%m-%d',
                'group' => "date(occurred_at)"
            ],
        };
    }

    private function aggregateActiveMinutes(int $userId, string $period, $start, $end)
    {
        $format = $this->periodFormat($period);
        
        return DB::table('user_heartbeats')
            ->select(
                DB::raw("${format['group']} as bucket"),
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
                DB::raw("${format['group']} as bucket"),
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
        
        $query = DB::table('users as u')
            ->leftJoin('departments as d', 'd.id', '=', 'u.department_id')
            ->leftJoin('user_heartbeats as hb', function($join) use ($start, $end) {
                $join->on('hb.user_id', '=', 'u.id')
                     ->whereBetween('hb.occurred_at', [$start, $end]);
            })
            ->leftJoin('entry_activity_logs as log', function($join) use ($start, $end, $category) {
                $join->on('log.user_id', '=', 'u.id')
                     ->whereBetween('log.occurred_at', [$start, $end]);
                
                if ($category) {
                    $join->where('log.category', $category);
                }
            })
            ->select(
                'd.id as department_id',
                'd.name as department_name',
                DB::raw("strftime('${dateFormat}', COALESCE(log.occurred_at, hb.occurred_at)) as bucket"),
                DB::raw('COUNT(DISTINCT hb.id) as heartbeats'),
                DB::raw('COALESCE(SUM(log.words_created), 0) as words_created'),
                DB::raw('COALESCE(SUM(log.words_edited), 0) as words_edited'),
                DB::raw('log.category as category')
            )
            ->when($departmentId, function($q) use ($departmentId) { 
                $q->where('u.department_id', $departmentId); 
            })
            ->whereNotNull('d.id')
            ->groupBy('department_id', 'department_name', 'bucket')
            ->when($category, function($q) use ($category) {
                $q->addSelect('log.category')
                  ->groupBy('log.category');
            })
            ->orderBy('department_name')
            ->orderBy('bucket');

        return $query->get();
    }

    private function authorizeAdmin(): void
    {
        $user = Auth::user();
        if (!$user || !$user->isAdmin()) {
            abort(403, 'Unauthorized');
        }
    }
}
