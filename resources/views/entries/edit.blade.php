<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('དྲི་བ་དྲིས་ལན་རྩོམ་སྒྲིག་བྱེད་པ།') }} (Edit Entry)
            </h2>
            <div>
                <a href="{{ route('entries.show', $entry) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                    {{ __('ཕྱིར་ལོག') }} (Back)
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if(in_array($entry->status, ['approved', 'rejected']) && $entry->canChangeStatus())
                        @php
                            $statusChangeTime = $entry->status_updated_at ?? $entry->updated_at;
                            $minutesLeft = $statusChangeTime ? 10 - $statusChangeTime->diffInMinutes(now()) : 10;
                            $secondsLeft = $statusChangeTime ? 600 - $statusChangeTime->diffInSeconds(now()) : 600;
                        @endphp
                        <div class="mb-6 p-4 bg-yellow-50 dark:bg-yellow-900 border border-yellow-200 dark:border-yellow-700 rounded-md" id="editing-window-warning">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-yellow-800 dark:text-yellow-200">
                                        {{ __('Editing Window Active') }}
                                    </h3>
                                    <div class="mt-2 text-sm text-yellow-700 dark:text-yellow-300">
                                        <p>
                                            {{ __('This entry has been') }} <strong>{{ ucfirst($entry->status) }}</strong> 
                                            {{ __('and you have') }} <strong id="time-remaining">{{ $minutesLeft }} {{ __('minutes') }}</strong> 
                                            {{ __('remaining to make changes. After this time, the status cannot be modified.') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <script>
                            // Dynamic countdown timer for editing window
                            (function() {
                                const timeRemainingElement = document.getElementById('time-remaining');
                                const warningElement = document.getElementById('editing-window-warning');
                                const statusSelect = document.getElementById('status');
                                
                                if (!timeRemainingElement || !warningElement) return;
                                
                                // Calculate initial time remaining in seconds
                                let totalSeconds = {{ $secondsLeft }};
                                let countdownInterval = null;
                                let hasExpired = false;
                                let hasBeenEdited = false;
                                
                                function endEditingWindow(reason) {
                                    if (hasExpired) return; // Prevent multiple executions
                                    
                                    hasExpired = true;
                                    
                                    // Clear the interval to prevent multiple executions
                                    if (countdownInterval) {
                                        clearInterval(countdownInterval);
                                        countdownInterval = null;
                                    }
                                    
                                    // Time expired - hide the warning and disable form
                                    warningElement.style.display = 'none';
                                    
                                    // Disable the status dropdown if it exists
                                    if (statusSelect) {
                                        statusSelect.disabled = true;
                                        statusSelect.style.opacity = '0.5';
                                    }
                                    
                                    // Check if expired message already exists to prevent duplicates
                                    const existingExpiredMessage = document.getElementById('expired-message');
                                    if (!existingExpiredMessage) {
                                        // Show expired message
                                        const expiredMessage = document.createElement('div');
                                        expiredMessage.id = 'expired-message';
                                        expiredMessage.className = 'mb-6 p-4 bg-red-50 dark:bg-red-900 border border-red-200 dark:border-red-700 rounded-md';
                                        
                                        let messageText = '';
                                        if (reason === 'edited') {
                                            messageText = '{{ __("Status has been edited. The editing window is now closed.") }}';
                                        } else {
                                            messageText = '{{ __("The 10-minute editing window has expired. Status cannot be modified.") }}';
                                        }
                                        
                                        expiredMessage.innerHTML = `
                                            <div class="flex">
                                                <div class="flex-shrink-0">
                                                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                                    </svg>
                                                </div>
                                                <div class="ml-3">
                                                    <h3 class="text-sm font-medium text-red-800 dark:text-red-200">
                                                        {{ __('Editing Window Closed') }}
                                                    </h3>
                                                    <div class="mt-2 text-sm text-red-700 dark:text-red-300">
                                                        <p>${messageText}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        `;
                                        warningElement.parentNode.insertBefore(expiredMessage, warningElement.nextSibling);
                                    }
                                }
                                
                                function updateCountdown() {
                                    if (hasExpired) return; // Prevent multiple executions
                                    
                                    if (totalSeconds <= 0) {
                                        endEditingWindow('timeout');
                                        return;
                                    }
                                    
                                    const minutes = Math.floor(totalSeconds / 60);
                                    const seconds = totalSeconds % 60;
                                    
                                    if (minutes > 0) {
                                        timeRemainingElement.textContent = `${minutes} {{ __('minutes') }} ${seconds} {{ __('seconds') }}`;
                                    } else {
                                        timeRemainingElement.textContent = `${seconds} {{ __('seconds') }}`;
                                        
                                        // Change color to red when less than 1 minute
                                        warningElement.className = 'mb-6 p-4 bg-red-50 dark:bg-red-900 border border-red-200 dark:border-red-700 rounded-md';
                                    }
                                    
                                    totalSeconds--;
                                }
                                
                                // Monitor status changes
                                if (statusSelect) {
                                    statusSelect.addEventListener('change', function() {
                                        if (!hasBeenEdited && !hasExpired) {
                                            hasBeenEdited = true;
                                            // End the editing window immediately when status is changed
                                            endEditingWindow('edited');
                                        }
                                    });
                                }
                                
                                // Update every second
                                countdownInterval = setInterval(updateCountdown, 1000);
                                
                                // Initial update
                                updateCountdown();
                            })();
                        </script>
                    @endif

                    <form method="POST" action="{{ route('entries.update', $entry) }}">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <x-input-label for="category" :value="__('སྡེ་ཚན། (Category)')" />
                                @php
                                    // Prepare helpers for category selection logic
                                    $categoryNames = $categories->pluck('name')->toArray();
                                    $currentCategory = old('category', $entry->category);
                                    $isExistingCategory = in_array($currentCategory, $categoryNames, true);
                                @endphp
                                <select id="category" name="category" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                    <option value="">{{ __('སྡེ་ཚན་གདམ་ཀ') }} (Select Category)</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->name }}" {{ $currentCategory == $category->name ? 'selected' : '' }}>{{ $category->tibetan_name ?: $category->name }}</option>
                                    @endforeach
                                    <option value="new_category" {{ !$isExistingCategory && $currentCategory ? 'selected' : '' }}>{{ __('གསར་པ་སྣོན།') }} (Add New)</option>
                                </select>

                                <div id="new_category_input" class="mt-2 {{ !$isExistingCategory && $currentCategory ? '' : 'hidden' }}">
                                    <x-text-input id="new_category" class="block w-full" type="text" value="{{ !$isExistingCategory ? $currentCategory : '' }}" placeholder="{{ __('སྡེ་ཚན་གསར་པ། (New Category)') }}" />
                                </div>
                                <x-input-error :messages="$errors->get('category')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="difficulty" :value="__('དཀའ་ཚད། (Difficulty)')" />
                                <select id="difficulty" name="difficulty" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                    <option value="1" {{ old('difficulty', $entry->difficulty) == 1 ? 'selected' : '' }}>1 - {{ __('ལས་སླ།') }} (Easy)</option>
                                    <option value="2" {{ old('difficulty', $entry->difficulty) == 2 ? 'selected' : '' }}>2 - {{ __('འབྲིང་ཙམ་ལས་སླ།') }} (Fairly Easy)</option>
                                    <option value="3" {{ old('difficulty', $entry->difficulty) == 3 ? 'selected' : '' }}>3 - {{ __('འབྲིང་།') }} (Medium)</option>
                                    <option value="4" {{ old('difficulty', $entry->difficulty) == 4 ? 'selected' : '' }}>4 - {{ __('འབྲིང་ཙམ་དཀའ་བ།') }} (Fairly Hard)</option>
                                    <option value="5" {{ old('difficulty', $entry->difficulty) == 5 ? 'selected' : '' }}>5 - {{ __('དཀའ་བ།') }} (Hard)</option>
                                </select>
                                <x-input-error :messages="$errors->get('difficulty')" class="mt-2" />
                            </div>
                        </div>

                        <div class="mb-6">
                            <x-input-label for="tags" :value="__('རྒྱབ་མཐའ། (Tags - select or add new)')" />
                            <div class="mt-1 relative">
                                <!-- Search Bar -->
                                <div class="mb-2">
                                    <input type="text" id="tagSearch" class="block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" 
                                           placeholder="{{ __('དོན་ཚན་བཙལ་བ། (Search tags)...') }}">
                                </div>
                                
                                <!-- Tags Container -->
                                <div id="tagsContainer" class="flex flex-wrap gap-2 mb-2 max-h-32 overflow-y-auto">
                                    @php $entryTagsArray = $entry->tags ? explode(',', $entry->tags) : []; @endphp
                                    @foreach($tags as $index => $tag)
                                        <label class="tag-item inline-flex items-center bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded-md text-sm {{ $index >= 6 ? 'hidden more-tag' : '' }}" 
                                               data-tag-name="{{ strtolower($tag->tibetan_name ?: $tag->name) }}">
                                            <input type="checkbox" name="selected_tags[]" value="{{ $tag->name }}" class="mr-1" {{ in_array($tag->name, $entryTagsArray) ? 'checked' : '' }}>
                                            {{ $tag->tibetan_name ?: $tag->name }}
                                        </label>
                                    @endforeach
                                </div>
                                
                                <!-- See More/Less Button -->
                                @if(count($tags) > 6)
                                    <button type="button" id="toggleTags" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline focus:outline-none">
                                        མུ་མཐུད་གཟིགས། ({{ count($tags) - 6 }} More)
                                    </button>
                                @endif
                                
                                <!-- New Tags Input -->
                                <x-text-input id="tags" class="block w-full mt-2" style="color: black;" type="text" name="tags" :value="old('tags')" placeholder="{{ __('གསར་པ་སྣོན། (Add new tags, comma separated)' ) }}" />
                                <p class="text-sm text-gray-500 mt-1">{{ __('དོན་ཚན་གསར་པ་ཁ་སྣོན་བྱེད་ན་དོན་ཚན་གཉིས་ཀྱི་པར་ལ་དབྱིན་ཇིའི་ཁོ་མ་ངེས་པར་དུ་འབྲི་དགོས།') }} (Separate new tags with commas)</p>
                                
                                <style>
                                    .tag-item {
                                        transition: all 0.3s ease;
                                    }
                                    #tagsContainer {
                                        scrollbar-width: thin;
                                        scrollbar-color: #c7d2fe #e0e7ff;
                                    }
                                    #tagsContainer::-webkit-scrollbar {
                                        width: 6px;
                                    }
                                    #tagsContainer::-webkit-scrollbar-track {
                                        background: #e0e7ff;
                                        border-radius: 3px;
                                    }
                                    #tagsContainer::-webkit-scrollbar-thumb {
                                        background-color: #c7d2fe;
                                        border-radius: 3px;
                                    }
                                </style>
                            </div>
                            <x-input-error :messages="$errors->get('tags')" class="mt-2" />
                        </div>

                        <div class="mb-6">
                            <x-input-label for="context" :value="__('རྒྱབ་ལྗོངས། (Context)')" />
                            <textarea
                                id="context"
                                name="context"
                                class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                rows="8"
                                required
                            >{{ old('context', $entry->context) }}</textarea>
                            <p class="text-sm text-gray-500 mt-1">{{ __('དྲི་ལན་གྱི་རྒྱབ་ལྗོངས་ཡིག་ཆ་འཇུག་ཡུལ།') }} (Provide relevant context for this question-answer pair)</p>
                            <x-input-error :messages="$errors->get('context')" class="mt-2" />
                        </div>

                        <div class="mb-6">
                            <x-input-label for="reference" :value="__('ཁུངས་ལུང་། (Reference)')" />
                            <textarea
                                id="reference"
                                name="reference"
                                class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                rows="4"
                            >{{ old('reference', $entry->reference) }}</textarea>
                            <p class="text-sm text-gray-500 mt-1">{{ __('ཁུངས་ལུང་ཇི་ཡིན་འདིར་འགོད་རོགས།') }} (Provide source references for this content)</p>
                            <x-input-error :messages="$errors->get('reference')" class="mt-2" />
                        </div>

                        <div class="mb-6">
                            <x-input-label for="question" :value="__('དྲི་བ། (Question)')" />
                            <textarea
                                id="question"
                                name="question"
                                class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                rows="6"
                                required
                            >{{ old('question', $entry->question) }}</textarea>
                            <x-input-error :messages="$errors->get('question')" class="mt-2" />
                        </div>

                        <div class="mb-6">
                            <x-input-label for="answer" :value="__('ལན། (Answer)')" />
                            <textarea
                                id="answer"
                                name="answer"
                                class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                rows="10"
                                required
                            >{{ old('answer', $entry->answer) }}</textarea>
                            <x-input-error :messages="$errors->get('answer')" class="mt-2" />
                        </div>

                        <!-- Status field - editable within 10-minute window for approved/rejected entries -->
                        <div class="mb-6">
                            <x-input-label for="status" :value="__('གནས་སྟངས། (Status)')" />
                            <div class="mt-1">
                                @if(in_array($entry->status, ['approved', 'rejected']) && $entry->canChangeStatus() && Auth::user()->canReviewContent())
                                    <!-- Show dropdown for approved/rejected entries within editing window -->
                                    <select id="status" name="status" class="block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                        <option value="draft" {{ $entry->status == 'draft' ? 'selected' : '' }}>{{ __('ཟིན་བྲིས།') }} (Draft)</option>
                                        <option value="pending" {{ $entry->status == 'pending' ? 'selected' : '' }}>{{ __('བསྐྱར་ཞིབ་ལ་བསྒུག་བཞིན་པ།') }} (Pending Review)</option>
                                        <option value="approved" {{ $entry->status == 'approved' ? 'selected' : '' }}>{{ __('ཆོག་མཆན་ཐོབ་པ།') }} (Approved)</option>
                                        <option value="rejected" {{ $entry->status == 'rejected' ? 'selected' : '' }}>{{ __('ངོས་ལེན་མ་བྱུང་བ།') }} (Rejected)</option>
                                    </select>
                                    <p class="text-sm text-green-600 dark:text-green-400 mt-1">{{ __('You can change the status within the 10-minute editing window.') }}</p>
                                @else
                                    <!-- Show read-only status display -->
                                    @switch($entry->status)
                                        @case('draft')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                {{ __('ཟིན་བྲིས།') }} (Draft)
                                            </span>
                                            @break
                                        @case('pending')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                {{ __('བསྐྱར་ཞིབ་ལ་བསྒུག་བཞིན་པ།') }} (Pending Review)
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

                                    <!-- Information message based on status -->
                                    @if($entry->status === 'pending')
                                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ __('དྲི་བ་དྲིས་ལན་འདི་བསྐྱར་ཞིབ་བྱེད་མཁན་ལ་ཕུལ་ཟིན་པས། དེའི་ནང་དོན་བཟོ་བཅོས་རྒྱག་མི་ཆོག') }} (This entry has been submitted for review and cannot be edited)</p>
                                    @elseif($entry->status === 'approved')
                                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ __('དྲི་བ་དྲིས་ལན་འདི་ལ་ཆོག་མཆན་ཐོབ་ཟིན་པས། དེའི་ནང་དོན་བཟོ་བཅོས་རྒྱག་མི་ཆོག') }} (This entry has been approved and cannot be edited)</p>
                                    @elseif($entry->status === 'rejected')
                                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ __('དྲི་བ་དྲིས་ལན་འདི་ལ་ངོས་ལེན་མ་བྱུང་བས། བཟོ་བཅོས་བཏང་ནས་བསྐྱར་དུ་ཕུལ་དགོས།') }} (This entry was rejected. Please edit and resubmit.)</p>

                                        <!-- Display feedback if available for rejected entries -->
                                        @if($entry->feedback)
                                        <div class="mt-2 p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-900/30 rounded-md">
                                            <h4 class="font-medium text-red-800 dark:text-red-300">{{ __('བསམ་འཆར།') }} (Feedback):</h4>
                                            <p class="text-red-700 dark:text-red-400">{{ $entry->feedback }}</p>
                                        </div>
                                        @endif
                                    @endif

                                    @if(in_array($entry->status, ['approved', 'rejected']) && !$entry->canChangeStatus())
                                        <p class="mt-2 text-sm text-red-500 dark:text-red-400">{{ __('Status cannot be changed. The 10-minute editing window has expired.') }}</p>
                                    @endif
                                @endif
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            @if($entry->status === 'draft' || $entry->status === 'rejected' || auth()->user()->isAdmin())
                                <x-primary-button class="ml-4">
                                    {{ __('ཉར་ཚགས།') }} (Save)
                                </x-primary-button>
                            @endif
                        </div>
                    </form>

                    <!-- Separate form for Submit for Review action -->
                    @if(($entry->status === 'draft' || $entry->status === 'rejected') && (auth()->user()->isAdmin() || auth()->id() == $entry->user_id))
                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('entries.submit-via-get', $entry) }}" class="ml-4 inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('བསྐྱར་ཞིབ་ལ་ཕུལ།') }} (Submit for Review)
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleBtn = document.getElementById('toggleTags');
            const tagSearch = document.getElementById('tagSearch');
            const tagsContainer = document.getElementById('tagsContainer');
            let showAll = false;
            
            // Function to sort tags with checked ones first
            function sortTags() {
                const tagItems = Array.from(document.querySelectorAll('.tag-item'));
                
                // Sort tags: checked first, then sort by name
                tagItems.sort((a, b) => {
                    const aChecked = a.querySelector('input[type="checkbox"]').checked;
                    const bChecked = b.querySelector('input[type="checkbox"]').checked;
                    
                    if (aChecked && !bChecked) return -1;
                    if (!aChecked && bChecked) return 1;
                    
                    const aName = a.getAttribute('data-tag-name');
                    const bName = b.getAttribute('data-tag-name');
                    return aName.localeCompare(bName);
                });
                
                // Re-append tags in new order
                tagItems.forEach(tag => tagsContainer.appendChild(tag));
                
                // Update visibility based on showAll state
                updateTagVisibility();
            }
            
            // Function to update tag visibility
            function updateTagVisibility() {
                const tagItems = document.querySelectorAll('.tag-item');
                tagItems.forEach((tag, index) => {
                    const isVisible = index < 6 || showAll || tag.querySelector('input[type="checkbox"]').checked;
                    tag.style.display = isVisible ? 'flex' : 'none';
                });
                
                // Update show more/less button text if it exists
                if (toggleBtn) {
                    const hiddenCount = Array.from(tagItems).filter((_, i) => i >= 6 && !showAll).length;
                    toggleBtn.textContent = showAll 
                        ? 'ཡོངས་བསྡུས། (Show Less)' 
                        : `མུ་མཐུད་གཟིགས། (${hiddenCount} More)`;
                }
            }
            
            // Initial sort
            sortTags();
            
            // Toggle show all/hide
            if (toggleBtn) {
                toggleBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    showAll = !showAll;
                    updateTagVisibility();
                });
            }
            
            // Search functionality
            if (tagSearch) {
                tagSearch.addEventListener('input', function(e) {
                    const searchTerm = e.target.value.toLowerCase();
                    const tagItems = document.querySelectorAll('.tag-item');
                    
                    tagItems.forEach(tag => {
                        const tagName = tag.getAttribute('data-tag-name');
                        const isVisible = tagName.includes(searchTerm);
                        tag.style.display = isVisible ? 'flex' : 'none';
                    });
                    
                    // Reset visibility when search is cleared
                    if (!searchTerm) {
                        updateTagVisibility();
                    }
                });
            }
            
            // Update sort when any checkbox is clicked
            document.addEventListener('change', function(e) {
                if (e.target.matches('input[type="checkbox"]')) {
                    sortTags();
                }
            });
        });
    </script>
</x-app-layout>
