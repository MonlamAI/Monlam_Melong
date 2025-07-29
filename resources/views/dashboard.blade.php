<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('མདུན་སྟེགས།') }} (Dashboard)
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- User Role Card -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium mb-4">{{ __('ལས་མིའི་གནས་ཚུལ།') }} (User Information)</h3>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg shadow">
                            <h4 class="font-medium text-lg mb-2">{{ __('ལས་མིའི་སྡེ་ཁག') }} (Your Role)</h4>
                            <div class="flex items-center mb-2">
                                <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-indigo-500 text-white mr-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                    </svg>
                                </span>
                                <span class="text-lg font-medium">
                                    @if (Auth::user()->isAdmin())
                                        {{ __('འཛིན་སྐྱོང་བ།') }} (Administrator)
                                    @elseif (Auth::user()->isChiefEditor())
                                        {{ __('རྩོམ་སྒྲིག་འགན་འཛིན།') }} (Chief Editor)
                                    @elseif (Auth::user()->isReviewer())
                                        {{ __('ཞུ་དག་པ།') }} (Reviewer)
                                    @elseif (Auth::user()->isBenchmarkEditor())
                                        {{ __('ཚད་འཇལ་རྩོམ་སྒྲིག་པ།') }} (Benchmark Editor)
                                    @elseif (Auth::user()->isEditor())
                                        {{ __('རྩོམ་སྒྲིག་པ།') }} (Editor)
                                    @else
                                        {{ __('སྤྱོད་མི།') }} (User)
                                    @endif
                                </span>
                            </div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('ཁྱེད་ཀྱི་ལས་མིའི་སྡེ་ཁག་གིས་ཚོགས་པའི་ནང་ཁྱེད་ཀྱིས་བྱེད་ཆོག་པའི་བྱ་གཞག་གཏན་འབེབས་བྱེད་པ་ཡིན།') }}</p>
                        </div>
                        
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg shadow">
                            <h4 class="font-medium text-lg mb-2">{{ __('དམིགས་བསལ་གྱི་ཐོབ་ཐང་།') }} (Special Permissions)</h4>
                            <ul class="space-y-2 text-sm">
                                <li class="flex items-center">
                                    <span class="inline-flex items-center justify-center h-5 w-5 rounded-full {{ Auth::user()->canManageTags() ? 'bg-green-500' : 'bg-gray-300 dark:bg-gray-600' }} mr-2">
                                        @if (Auth::user()->canManageTags())
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-white" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                            </svg>
                                        @else
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-white" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        @endif
                                    </span>
                                    {{ __('ཚིག་སྒྲོམ་དོ་དམ།') }} (Tag Management)
                                </li>
                                <li class="flex items-center">
                                    <span class="inline-flex items-center justify-center h-5 w-5 rounded-full {{ Auth::user()->canManageBenchmarks() ? 'bg-green-500' : 'bg-gray-300 dark:bg-gray-600' }} mr-2">
                                        @if (Auth::user()->canManageBenchmarks())
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-white" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                            </svg>
                                        @else
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-white" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        @endif
                                    </span>
                                    {{ __('ཚད་འཇལ་དོ་དམ།') }} (Benchmark Management)
                                </li>
                                <li class="flex items-center">
                                    <span class="inline-flex items-center justify-center h-5 w-5 rounded-full {{ Auth::user()->canManageCategories() ? 'bg-green-500' : 'bg-gray-300 dark:bg-gray-600' }} mr-2">
                                        @if (Auth::user()->canManageCategories())
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-white" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                            </svg>
                                        @else
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-white" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        @endif
                                    </span>
                                    {{ __('སྡེ་ཚན་དོ་དམ།') }} (Category Management)
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Stats Overview Card -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium mb-4">{{ __('སྤྱི་ཡོངས་ཀྱི་གནས་སྟངས།') }} (Overview)</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <!-- Entries Stats -->
                        <div class="bg-white dark:bg-gray-700 p-4 rounded-lg shadow">
                            <h4 class="font-medium text-lg mb-2">{{ __('ནང་འཇུག་ཟིན་པ།') }} (Entries)</h4>
                            <p class="text-3xl font-bold">{{ App\Models\MonlamMelongFinetuning::count() }}</p>
                            <div class="mt-2 text-sm">
                                <div class="flex items-center justify-between mb-1">
                                    <span>{{ __('ཟིན་བྲིས།') }} (Draft):</span>
                                    <span class="font-medium">{{ App\Models\MonlamMelongFinetuning::where('status', 'draft')->count() }}</span>
                                </div>
                                <div class="flex items-center justify-between mb-1">
                                    <span>{{ __('བསྐྱར་ཞིབ་བྱེད་བཞིན་པ།') }} (Pending):</span>
                                    <span class="font-medium">{{ App\Models\MonlamMelongFinetuning::where('status', 'pending')->count() }}</span>
                                </div>
                                <div class="flex items-center justify-between mb-1">
                                    <span>{{ __('ངོས་ལེན་བྱུང་བ།') }} (Approved):</span>
                                    <span class="font-medium">{{ App\Models\MonlamMelongFinetuning::where('status', 'approved')->count() }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span>{{ __('ངོས་ལེན་མ་བྱུང་བ།') }} (Rejected):</span>
                                    <span class="font-medium">{{ App\Models\MonlamMelongFinetuning::where('status', 'rejected')->count() }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Categories Stats -->
                        <div class="bg-white dark:bg-gray-700 p-4 rounded-lg shadow">
                            <h4 class="font-medium text-lg mb-2">{{ __('སྡེ་ཚན།') }} (Categories)</h4>
                            <p class="text-3xl font-bold">{{ App\Models\MonlamMelongFinetuning::distinct('category')->whereNotNull('category')->count('category') }}</p>
                            <div class="mt-4 text-sm">
                                <a href="{{ route('entries.index') }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300">
                                    {{ __('སྡེ་ཚན་ཁག་ལ་ལྟ་བ།') }} (View categories) →
                                </a>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="bg-white dark:bg-gray-700 p-4 rounded-lg shadow">
                            <h4 class="font-medium text-lg mb-2">{{ __('ལས་ཀ་བྱེད་པ།') }} (Actions)</h4>
                            <div class="flex flex-col space-y-2">
                                <a href="{{ route('entries.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                    {{ __('ནང་འཇུག་གསར་པ་བཟོ་བ།') }} (New entry)
                                </a>
                                @if(Auth::user()->isAdmin() || Auth::user()->isReviewer())
                                    <a href="{{ route('entries.review-queue') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 active:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                        {{ __('བསྐྱར་ཞིབ་བྱེད་པ།') }} (Review entries)
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
