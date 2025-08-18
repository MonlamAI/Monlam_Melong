<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Admin Reports') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="GET" class="mb-4 flex gap-3 items-end">
                        <div>
                            <label class="block text-sm mb-1">Period</label>
                            <select name="period" class="border rounded px-2 py-1 text-black">
                                @foreach (['hourly','daily','weekly','monthly','yearly'] as $p)
                                    <option value="{{ $p }}" @selected($period===$p)>{{ ucfirst($p) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm mb-1">Department</label>
                            <select name="department_id" class="border rounded px-2 py-1 text-black">
                                <option value="">All</option>
                                @foreach ($departments as $dept)
                                    <option value="{{ $dept->id }}" @selected((string)$departmentId===(string)$dept->id)>
                                        {{ $dept->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm mb-1">Start</label>
                            <input type="date" name="start" value="{{ optional($start)->toDateString() }}" class="border rounded px-2 py-1 text-black" />
                        </div>
                        <div>
                            <label class="block text-sm mb-1">End</label>
                            <input type="date" name="end" value="{{ optional($end)->toDateString() }}" class="border rounded px-2 py-1 text-black" />
                        </div>
                        <div>
                            <label class="block text-sm mb-1">Category</label>
                            <select name="category" class="border rounded px-2 py-1 text-black">
                                <option value="">All Categories</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->name }}" @selected($selectedCategory === $cat->name)>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button class="bg-indigo-600 text-white px-3 py-2 rounded">Apply</button>
                    </form>

                    <h3 class="font-semibold mb-2">Department Summary</h3>
                    <table class="w-full text-left text-sm mb-8">
                        <thead>
                            <tr>
                                <th class="border-b py-1">Department</th>
                                <th class="border-b py-1">Category</th>
                                <th class="border-b py-1">Bucket</th>
                                <th class="border-b py-1">Heartbeats</th>
                                <th class="border-b py-1">Words Created</th>
                                <th class="border-b py-1">Words Edited</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($deptSummaries as $deptSummary)
                                <tr>
                                    <td class="border-b py-1">{{ $deptSummary->department_name ?? 'N/A' }}</td>
                                    <td class="border-b py-1">{{ $deptSummary->category ?? 'All Categories' }}</td>
                                    <td class="border-b py-1">{{ $deptSummary->bucket ?? 'N/A' }}</td>
                                    <td class="border-b py-1">{{ number_format($deptSummary->heartbeats ?? 0) }}</td>
                                    <td class="border-b py-1">{{ number_format($deptSummary->words_created ?? 0) }}</td>
                                    <td class="border-b py-1">{{ (int)($deptSummary->words_edited ?? 0) }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="py-2">No data</td></tr>
                            @endforelse
                        </tbody>
                    </table>

                    <h3 class="font-semibold mb-2">Department Users</h3>
                    <ul class="list-disc pl-5">
                        @forelse ($users as $u)
                            <li>{{ $u->name }} @if($u->department) ({{ $u->department->name }}) @endif</li>
                        @empty
                            <li>No users</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
