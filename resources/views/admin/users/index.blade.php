<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('ལས་མི་དོ་དམ།') }} (User Management)
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium">{{ __('སྤྱོད་མི་ཚང་མ།') }} (All Users)</h3>
                        <a href="{{ route('admin.users.create') }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150">
                            {{ __('སྤྱོད་མི་གསར་སྣོན།') }} (Add New User)
                        </a>
                    </div>

                    @if(session('success'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Active Filters Display -->
                    @if($filters['hasFilters'])
                    <div class="mb-4 p-3 bg-gray-50 dark:bg-gray-700 rounded-md">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-2">
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Active Filters') }}:</span>
                                @if($filters['role'])
                                    @php
                                        $roleLabels = [
                                            'admin' => ['tibetan' => 'འཛིན་སྐྱོང་པ།', 'english' => 'Admin'],
                                            'chief_editor' => ['tibetan' => 'རྩོམ་སྒྲིག་དབུ་ཆེ།', 'english' => 'Chief Editor'],
                                            'benchmark_editor' => ['tibetan' => 'ཚད་འཇལ་རྩོམ་སྒྲིག།', 'english' => 'Benchmark Editor'],
                                            'reviewer' => ['tibetan' => 'ཞུ་དག་པ།', 'english' => 'Reviewer'],
                                            'editor' => ['tibetan' => 'རྩོམ་སྒྲིག་པ།', 'english' => 'Editor'],
                                        ];
                                        $roleLabel = $roleLabels[$filters['role']] ?? ['tibetan' => ucfirst($filters['role']), 'english' => ucfirst($filters['role'])];
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                        {{ __('འགན་འཛིན།') }}: {{ $roleLabel['tibetan'] }} ({{ $roleLabel['english'] }})
                                    </span>
                                @endif
                                @if($filters['search'])
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                        {{ __('Search') }}: "{{ $filters['search'] }}"
                                    </span>
                                @endif
                            </div>
                            <a href="{{ route('admin.users.index') }}" class="text-sm text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">
                                {{ __('Clear All') }}
                            </a>
                        </div>
                    </div>
                    @endif

                    <!-- Filters -->
                    <div class="mb-6">
                        <form action="{{ route('admin.users.index') }}" method="GET" class="flex items-end gap-4 flex-wrap">
                            <div>
                                <label for="role" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('འགན་འཛིན།') }} (Role)</label>
                                <select name="role" id="role" class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="">{{ __('འགན་འཛིན་ཚང་མ།') }} (All Roles)</option>
                                    @foreach($roles as $role)
                                        @php
                                            $roleLabels = [
                                                'admin' => ['tibetan' => 'འཛིན་སྐྱོང་པ།', 'english' => 'Admin'],
                                                'chief_editor' => ['tibetan' => 'རྩོམ་སྒྲིག་དབུ་ཆེ།', 'english' => 'Chief Editor'],
                                                'benchmark_editor' => ['tibetan' => 'ཚད་འཇལ་རྩོམ་སྒྲིག།', 'english' => 'Benchmark Editor'],
                                                'reviewer' => ['tibetan' => 'ཞུ་དག་པ།', 'english' => 'Reviewer'],
                                                'editor' => ['tibetan' => 'རྩོམ་སྒྲིག་པ།', 'english' => 'Editor'],
                                            ];
                                            $roleLabel = $roleLabels[$role] ?? ['tibetan' => ucfirst($role), 'english' => ucfirst($role)];
                                        @endphp
                                        <option value="{{ $role }}" {{ request('role') == $role ? 'selected' : '' }}>{{ $roleLabel['tibetan'] }} ({{ $roleLabel['english'] }})</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('འཚོལ།') }} (Search)</label>
                                <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="{{ __('Search by name or email...') }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                            </div>

                            <div class="flex space-x-2">
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                    {{ __('འཚོལ།') }} (Filter)
                                </button>
                                @if($filters['hasFilters'])
                                    <a href="{{ route('admin.users.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 focus:bg-gray-600 active:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                        {{ __('Clear All') }}
                                    </a>
                                @endif
                            </div>
                        </form>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white dark:bg-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-600">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('མིང་།') }} (Name)</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('གློག་འཕྲིན།') }} (Email)</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('འགན་འཛིན།') }} (Role)</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('སྡེ་ཚན།') }} (Categories)</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('ཐག་གཅོད།') }} (Actions)</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($users as $user)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $user->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $user->email }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            @switch($user->role)
                                                @case('admin')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800 dark:bg-purple-800 dark:text-purple-100">
                                                        {{ __('འཛིན་སྐྱོང་པ།') }} (Admin)
                                                    </span>
                                                    @break
                                                
                                                @case('chief_editor')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100">
                                                        {{ __('རྩོམ་སྒྲིག་དབུ་ཆེ།') }} (Chief Editor)
                                                    </span>
                                                    @break
                                                
                                                @case('benchmark_editor')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-cyan-100 text-cyan-800 dark:bg-cyan-800 dark:text-cyan-100">
                                                        {{ __('ཚད་འཇལ་རྩོམ་སྒྲིག།') }} (Benchmark Editor)
                                                    </span>
                                                    @break
                                                
                                                @case('reviewer')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100">
                                                        {{ __('ཞུ་དག་པ།') }} (Reviewer)
                                                    </span>
                                                    @break
                                                
                                                @default
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">
                                                        {{ __('རྩོམ་སྒྲིག་པ།') }} (Editor)
                                                    </span>
                                            @endswitch
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            @if(($user->role === 'editor' || $user->role === 'benchmark_editor') && !empty($user->allowed_categories))
                                                <span class="text-xs">{{ implode(', ', $user->allowed_categories) }}</span>
                                            @elseif($user->role === 'editor' || $user->role === 'benchmark_editor')
                                                <span class="text-xs italic">{{ __('ཚང་མ།') }} (All)</span>
                                            @else
                                                <span class="text-xs">-</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            <a href="{{ route('admin.users.edit', $user) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                                {{ __('བཟོ་བཅོས།') }} (Edit)
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $users->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    // This ensures that any dynamically added pagination links also maintain the filters
    document.addEventListener('DOMContentLoaded', function() {
        // Get all pagination links
        const paginationLinks = document.querySelectorAll('.pagination a');
        const currentUrl = new URL(window.location.href);
        const searchParams = new URLSearchParams(currentUrl.search);
        
        // Add current filters to pagination links
        paginationLinks.forEach(link => {
            const linkUrl = new URL(link.href);
            searchParams.forEach((value, key) => {
                if (key !== 'page') {
                    linkUrl.searchParams.set(key, value);
                }
            });
            link.href = linkUrl.toString();
        });
    });
</script>
