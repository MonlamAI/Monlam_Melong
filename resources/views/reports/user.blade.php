<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('ངའི་བྱེད་སྒོའི་སྙོམས་འགྲུབ།') }} (My Activity & Productivity)
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <!-- Filters -->
                    <div class="mb-6">
                        @if($selectedCategory || $selectedStatus)
                        <div class="mb-4 p-3 bg-gray-50 dark:bg-gray-700 rounded-md">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-2">
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Active Filters') }}:</span>
                                    @if($selectedCategory)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                            {{ __('སྡེ་ཚན།') }}: {{ $categories->firstWhere('name', $selectedCategory)->tibetan_name ?? $selectedCategory }}
                                        </span>
                                    @endif
                                    @if($selectedStatus)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                            {{ __('གནས་སྟངས།') }}: {{ __(ucfirst($selectedStatus)) }}
                                        </span>
                                    @endif
                                </div>
                                <a href="{{ route('reports.user') }}" class="text-sm text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">
                                    {{ __('Clear All') }}
                                </a>
                            </div>
                        </div>
                        @endif
                        
                        <form action="{{ route('reports.user') }}" method="GET" class="flex items-end gap-4 flex-wrap">
                            <div>
                                <label for="category" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('སྡེ་ཚན།') }} (Category)</label>
                                <select name="category" id="category" class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="">{{ __('སྡེ་ཚན་ཚང་མ།') }} (All Categories)</option>
                                    @foreach($categories as $category)
                                        @if($category)
                                            <option value="{{ $category->name }}" {{ $selectedCategory == $category->name ? 'selected' : '' }}>{{ $category->tibetan_name ?: $category->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('གནས་སྟངས།') }} (Status)</label>
                                <select name="status" id="status" class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="">{{ __('གནས་སྟངས་ཚང་མ།') }} (All Statuses)</option>
                                    <option value="draft" {{ $selectedStatus == 'draft' ? 'selected' : '' }}>{{ __('ཟིན་བྲིས།') }} (Draft)</option>
                                    <option value="pending" {{ $selectedStatus == 'pending' ? 'selected' : '' }}>{{ __('བསྐྱར་ཞིབ་ལ་བསྒུག་བཞིན་པ།') }} (Pending Review)</option>
                                    <option value="approved" {{ $selectedStatus == 'approved' ? 'selected' : '' }}>{{ __('ཆོག་མཆན་ཐོབ་པ།') }} (Approved)</option>
                                    <option value="rejected" {{ $selectedStatus == 'rejected' ? 'selected' : '' }}>{{ __('ངོས་ལེན་མ་བྱུང་བ།') }} (Rejected)</option>
                                </select>
                            </div>

                            <div>
                                <label for="period" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Period') }}</label>
                                <select name="period" id="period" class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                    @foreach (['hourly','daily','weekly','monthly','yearly'] as $p)
                                        <option value="{{ $p }}" {{ $period === $p ? 'selected' : '' }}>{{ ucfirst($p) }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="start" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Start Date') }}</label>
                                <input type="date" name="start" id="start" value="{{ optional($start)->toDateString() }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" />
                            </div>

                            <div>
                                <label for="end" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('End Date') }}</label>
                                <input type="date" name="end" id="end" value="{{ optional($end)->toDateString() }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" />
                            </div>

                            <div class="flex space-x-2">
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                    {{ __('འཚོལ།') }} (Filter)
                                </button>
                                @if($selectedCategory || $selectedStatus)
                                    <a href="{{ route('reports.user') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 focus:bg-gray-600 active:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                        {{ __('Clear All') }}
                                    </a>
                                @endif
                            </div>
                        </form>
                    </div>

                    <!-- Summary Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                        <div class="bg-blue-50 dark:bg-blue-900 p-6 rounded-lg">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-blue-600 dark:text-blue-400">{{ __('My Entries') }}</p>
                                    <p class="text-2xl font-semibold text-blue-900 dark:text-blue-100">{{ number_format($reports['summary']['total_entries']) }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-green-50 dark:bg-green-900 p-6 rounded-lg">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-green-600 dark:text-green-400">{{ __('Categories') }}</p>
                                    <p class="text-2xl font-semibold text-green-900 dark:text-green-100">{{ number_format($reports['summary']['categories_count']) }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-yellow-50 dark:bg-yellow-900 p-6 rounded-lg">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-8 w-8 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-yellow-600 dark:text-yellow-400">{{ __('Words Written') }}</p>
                                    <p class="text-2xl font-semibold text-yellow-900 dark:text-yellow-100">{{ number_format($userWordCounts) }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-purple-50 dark:bg-purple-900 p-6 rounded-lg">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-8 w-8 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-purple-600 dark:text-purple-400">{{ __('Avg Difficulty') }}</p>
                                    <p class="text-2xl font-semibold text-purple-900 dark:text-purple-100">{{ number_format($reports['summary']['avg_difficulty'], 1) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- My Category Statistics -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold mb-4">{{ __('ངའི་སྡེ་ཚན་ཚོགས་ཁག་གི་སྙོམས་འགྲུབ།') }} (My Category Statistics)</h3>
                        @if($reports['category_stats']->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="w-full text-left text-sm border-collapse">
                                    <thead class="bg-gray-50 dark:bg-gray-700">
                                        <tr>
                                            <th class="border-b py-2 px-3 font-medium">{{ __('སྡེ་ཚན།') }} (Category)</th>
                                            <th class="border-b py-2 px-3 font-medium text-right">{{ __('ཟིན་བྲིས།') }} (Draft)</th>
                                            <th class="border-b py-2 px-3 font-medium text-right">{{ __('བསྐྱར་ཞིབ་ལ་བསྒུག་བཞིན་པ།') }} (Pending)</th>
                                            <th class="border-b py-2 px-3 font-medium text-right">{{ __('ཆོག་མཆན་ཐོབ་པ།') }} (Approved)</th>
                                            <th class="border-b py-2 px-3 font-medium text-right">{{ __('ངོས་ལེན་མ་བྱུང་བ།') }} (Rejected)</th>
                                            <th class="border-b py-2 px-3 font-medium text-right">{{ __('Total') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($reports['category_stats'] as $category => $periods)
                                            @php
                                                $totalDraft = $periods->sum('by_status.draft');
                                                $totalPending = $periods->sum('by_status.pending');
                                                $totalApproved = $periods->sum('by_status.approved');
                                                $totalRejected = $periods->sum('by_status.rejected');
                                                $total = $totalDraft + $totalPending + $totalApproved + $totalRejected;
                                            @endphp
                                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                                <td class="border-b py-2 px-3 font-medium">{{ $categories->firstWhere('name', $category)->tibetan_name ?? $category }}</td>
                                                <td class="border-b py-2 px-3 text-right">{{ number_format($totalDraft) }}</td>
                                                <td class="border-b py-2 px-3 text-right">{{ number_format($totalPending) }}</td>
                                                <td class="border-b py-2 px-3 text-right">{{ number_format($totalApproved) }}</td>
                                                <td class="border-b py-2 px-3 text-right">{{ number_format($totalRejected) }}</td>
                                                <td class="border-b py-2 px-3 text-right font-semibold">{{ number_format($total) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                                <p>{{ __('No category data available for the selected period and filters.') }}</p>
                            </div>
                        @endif
                    </div>

                    <!-- My Time Series -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold mb-4">{{ __('ངའི་དུས་ཚོགས་ཀྱི་སྙོམས་འགྲུབ།') }} (My Activity Timeline)</h3>
                        @if($reports['time_series']->count() > 0)
                            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg border">
                                <div class="overflow-x-auto">
                                    <table class="w-full text-left text-sm border-collapse">
                                        <thead class="bg-gray-50 dark:bg-gray-700">
                                            <tr>
                                                <th class="border-b py-2 px-3 font-medium">{{ __('Date') }}</th>
                                                <th class="border-b py-2 px-3 font-medium text-right">{{ __('Total Entries') }}</th>
                                                @foreach($reports['category_stats']->keys() as $category)
                                                    <th class="border-b py-2 px-3 font-medium text-right">{{ $categories->firstWhere('name', $category)->tibetan_name ?? $category }}</th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($reports['time_series'] as $period)
                                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                                    <td class="border-b py-2 px-3">{{ $period['date']->format('Y-m-d') }}</td>
                                                    <td class="border-b py-2 px-3 text-right font-semibold">{{ number_format($period['total_entries']) }}</td>
                                                    @foreach($reports['category_stats']->keys() as $category)
                                                        <td class="border-b py-2 px-3 text-right">{{ number_format($period['by_category'][$category] ?? 0) }}</td>
                                                    @endforeach
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                                <p>{{ __('No timeline data available for the selected period.') }}</p>
                            </div>
                        @endif
                    </div>

                    <!-- My Status Breakdown -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold mb-4">{{ __('ངའི་གནས་སྟངས་ཚོགས་ཁག་གི་སྙོམས་འགྲུབ།') }} (My Status Breakdown)</h3>
                        @if($reports['status_stats']->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                @foreach ($reports['status_stats'] as $status => $periods)
                                    @php
                                        $total = $periods->sum();
                                        $statusLabels = [
                                            'draft' => ['tibetan' => 'ཟིན་བྲིས།', 'english' => 'Draft', 'color' => 'gray'],
                                            'pending' => ['tibetan' => 'བསྐྱར་ཞིབ་ལ་བསྒུག་བཞིན་པ།', 'english' => 'Pending Review', 'color' => 'yellow'],
                                            'approved' => ['tibetan' => 'ཆོག་མཆན་ཐོབ་པ།', 'english' => 'Approved', 'color' => 'green'],
                                            'rejected' => ['tibetan' => 'ངོས་ལེན་མ་བྱུང་བ།', 'english' => 'Rejected', 'color' => 'red'],
                                        ];
                                        $label = $statusLabels[$status] ?? ['tibetan' => ucfirst($status), 'english' => ucfirst($status), 'color' => 'gray'];
                                    @endphp
                                    <div class="bg-{{ $label['color'] }}-50 dark:bg-{{ $label['color'] }}-900 p-4 rounded-lg">
                                        <div class="text-sm font-medium text-{{ $label['color'] }}-600 dark:text-{{ $label['color'] }}-400">
                                            {{ $label['tibetan'] }} ({{ $label['english'] }})
                                        </div>
                                        <div class="text-2xl font-semibold text-{{ $label['color'] }}-900 dark:text-{{ $label['color'] }}-100">
                                            {{ number_format($total) }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                                <p>{{ __('No status data available for the selected period.') }}</p>
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    (function() {
        const periodEl = document.getElementById('period');
        const startEl = document.getElementById('start');
        const endEl = document.getElementById('end');

        if (!periodEl || !startEl || !endEl) return;

        function formatDate(date) {
            const y = date.getFullYear();
            const m = String(date.getMonth() + 1).padStart(2, '0');
            const d = String(date.getDate()).padStart(2, '0');
            return `${y}-${m}-${d}`;
        }

        function addDays(date, days) {
            const copy = new Date(date);
            copy.setDate(copy.getDate() + days);
            return copy;
        }

        function adjustDatesForPeriod(period) {
            const today = new Date();
            let end = today;
            let start = today;

            switch (period) {
                case 'hourly':
                    // Today only
                    start = today;
                    end = today;
                    break;
                case 'daily':
                    // Last 7 days including today
                    start = addDays(today, -7);
                    end = today;
                    break;
                case 'weekly':
                    // Last 4 weeks (~28 days)
                    start = addDays(today, -28);
                    end = today;
                    break;
                case 'monthly':
                    // Last 6 months (~180 days)
                    start = addDays(today, -180);
                    end = today;
                    break;
                case 'yearly':
                    // Last 12 months (~365 days)
                    start = addDays(today, -365);
                    end = today;
                    break;
                default:
                    // Safe fallback
                    start = addDays(today, -7);
                    end = today;
            }

            startEl.value = formatDate(start);
            endEl.value = formatDate(end);
        }

        // Change handler
        periodEl.addEventListener('change', function() {
            adjustDatesForPeriod(this.value);
        });
    })();
</script>