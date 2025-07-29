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
                    <form method="POST" action="{{ route('entries.update', $entry) }}">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <x-input-label for="category" :value="__('སྡེ་ཚན། (Category)')" />
                                <select id="category" name="category" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                    <option value="">{{ __('སྡེ་ཚན་གདམ་ཀ') }} (Select Category)</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->name }}" {{ old('category', $entry->category) == $category->name ? 'selected' : '' }}>{{ $category->tibetan_name ?: $category->name }}</option>
                                    @endforeach
                                    <option value="new_category" {{ !in_array(old('category', $entry->category), $categories->toArray()) && old('category', $entry->category) ? 'selected' : '' }}>{{ __('གསར་པ་སྣོན།') }} (Add New)</option>
                                </select>

                                <div id="new_category_input" class="mt-2 {{ !in_array(old('category', $entry->category), $categories->toArray()) && old('category', $entry->category) ? '' : 'hidden' }}">
                                    <x-text-input id="new_category" class="block w-full" type="text" value="{{ !in_array(old('category', $entry->category), $categories->toArray()) ? old('category', $entry->category) : '' }}" placeholder="{{ __('སྡེ་ཚན་གསར་པ། (New Category)') }}" />
                                    <p class="text-sm text-gray-500 mt-1">{{ __('སྡེ་ཚན་གསར་པ་བྲིས་ནས་ཟིན་བྲིས་ཉར་ཚགས་བྱེད་དུས་རང་འགུལ་གྱིས་སྣོན་ངེས།') }}</p>
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
                                <div class="flex flex-wrap gap-2 mb-2">
                                    @php $entryTagsArray = $entry->tags ? explode(',', $entry->tags) : []; @endphp
                                    @foreach($tags as $tag)
                                        <label class="inline-flex items-center bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded-md text-sm">
                                            <input type="checkbox" name="selected_tags[]" value="{{ $tag->name }}" class="mr-1" {{ in_array($tag->name, $entryTagsArray) ? 'checked' : '' }}>
                                            {{ $tag->tibetan_name ?: $tag->name }}
                                        </label>
                                    @endforeach
                                </div>
                                <x-text-input id="tags" class="block w-full" type="text" name="tags" :value="old('tags')" placeholder="{{ __('གསར་པ་སྣོན། (Add new tags, comma separated)') }}" />
                                <p class="text-sm text-gray-500 mt-1">{{ __('དོན་ཚན་གསར་པ་ཁ་སྣོན་བྱེད་ན་དོན་ཚན་གཉིས་ཀྱི་པར་ལ་དབྱིན་ཇིའི་ཁོ་མ་ངེས་པར་དུ་འབྲི་དགོས།') }} (Separate new tags with commas)</p>
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

                        <!-- Status display only - cannot be edited directly -->
                        <div class="mb-6">
                            <x-input-label for="status" :value="__('གནས་སྟངས། (Status)')" />
                            <div class="mt-1">
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
</x-app-layout>
