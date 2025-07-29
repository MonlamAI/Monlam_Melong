<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('སྨོན་ལམ་མེ་ལོང་དྲི་བ་དྲིས་ལན་') }} (Monlam Melong Q&A)
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h3 class="text-lg font-medium">{{ __('དྲི་བ་དྲིས་ལན་ཁག') }} (Questions and Answers)</h3>
                        </div>
                        @if(auth()->user()->isAdmin() || auth()->user()->isEditor())
                            <div>
                                <a href="{{ route('entries.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                    {{ __('དྲི་བ་དང་ལན་གསར་པ།') }} (New Entry)
                                </a>
                            </div>
                        @endif
                    </div>

                    <!-- Filters -->
                    <div class="mb-6">
                        <form action="{{ route('entries.index') }}" method="GET" class="flex items-end gap-4 flex-wrap">
                            <div>
                                <label for="category" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('སྡེ་ཚན།') }} (Category)</label>
                                <select name="category" id="category" class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="">{{ __('སྡེ་ཚན་ཚང་མ།') }} (All Categories)</option>
                                    @foreach($categories as $category)
                                        @if($category)
                                            <option value="{{ $category->name }}" {{ request('category') == $category->name ? 'selected' : '' }}>{{ $category->tibetan_name ?: $category->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>

                            @if(auth()->user()->isAdmin() || auth()->user()->isReviewer())
                                <div>
                                    <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('གནས་སྟངས།') }} (Status)</label>
                                    <select name="status" id="status" class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                        <option value="">{{ __('གནས་སྟངས་ཚང་མ།') }} (All Statuses)</option>
                                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>{{ __('ཟིན་བྲིས།') }} (Draft)</option>
                                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>{{ __('བསྐྱར་ཞིབ་ལ་བསྒུག་བཞིན་པ།') }} (Pending Review)</option>
                                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>{{ __('ཆོག་མཆན་ཐོབ་པ།') }} (Approved)</option>
                                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>{{ __('ངོས་ལེན་མ་བྱུང་བ།') }} (Rejected)</option>
                                    </select>
                                </div>
                            @endif

                            <div>
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                    {{ __('འཚོལ།') }} (Filter)
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Entries table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('དྲི་བ།') }} (Question)</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('སྡེ་ཚན།') }} (Category)</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('དཀའ་ཚད།') }} (Difficulty)</th>
                                    @if(auth()->user()->isAdmin())
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('རྩོམ་སྒྲིག་མཁན།') }} (Author)</th>
                                    @endif
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('གནས་སྟངས།') }} (Status)</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('ཐག་གཅོད།') }} (Actions)</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($entries as $entry)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            <div class="truncate max-w-xs">{{ Str::limit($entry->question, 50) }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $entry->category ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $entry->difficulty ?? '1' }}
                                        </td>
                                        @if(auth()->user()->isAdmin())
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                {{ $entry->user->name ?? 'Unknown' }}
                                            </td>
                                        @endif
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            @switch($entry->status)
                                                @case('draft')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                        {{ __('ཟིན་བྲིས།') }} (Draft)
                                                    </span>
                                                    @break
                                                @case('pending')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                        {{ __('བསྐྱར་ཞིབ་ལ་བསྒུག་བཞིན་པ།') }} (Pending)
                                                    </span>
                                                    @break
                                                @case('approved')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                        {{ __('ཆོག་མཆན་ཐོབ་པ།') }} (Approved)
                                                    </span>
                                                    @break
                                                @case('rejected')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                        {{ __('ངོས་ལེན་མ་བྱུང་བ།') }} (Rejected)
                                                    </span>
                                                    @break
                                                @default
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                        {{ $entry->status }}
                                                    </span>
                                            @endswitch
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 space-x-2">
                                            <a href="{{ route('entries.show', $entry) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">{{ __('ལྟ་བ།') }}</a>

                                            @if(auth()->user()->isAdmin() || (auth()->id() == $entry->user_id && $entry->status === 'draft'))
                                                <a href="{{ route('entries.edit', $entry) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">{{ __('བཟོ་བཅོས།') }}</a>
                                            @endif

                                            @if($entry->status === 'draft' && (auth()->user()->isAdmin() || auth()->id() == $entry->user_id))
                                                <form action="{{ route('entries.submit-for-review', $entry) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300">{{ __('བསྐྱར་ཞིབ་ལ་ཕུལ།') }}</button>
                                                </form>
                                            @endif

                                            @if(auth()->user()->isAdmin() || auth()->id() == $entry->user_id)
                                                <form action="{{ route('entries.destroy', $entry) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('གཏན་འཁེལ་ཡིན་ནམ?') }}');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">{{ __('འདོར།') }}</button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-center">
                                            {{ __('དྲི་བ་དྲིས་ལན་མི་འདུག') }} (No entries found)
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $entries->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
