<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('དྲི་བ་དྲིས་ལན་གསར་བཟོ།') }} (Create New Entry)
            </h2>
            <div>
                <a href="{{ route('entries.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                    {{ __('ཕྱིར་ལོག') }} (Back)
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('entries.store') }}">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <x-input-label for="category" :value="__('སྡེ་ཚན། (Category)')" />
                                <select id="category" name="category" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                    <option value="">{{ __('སྡེ་ཚན་གདམ་ཀ') }} (Select Category)</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->name }}" {{ old('category') == $category->name ? 'selected' : '' }}>{{ $category->tibetan_name ?: $category->name }}</option>
                                    @endforeach
                                    @if(Auth::user()->isAdmin())
                                    <option value="new_category">{{ __('གསར་པ་སྣོན།') }} (Add New)</option>
                                    @endif
                                </select>
                                <div id="new_category_input" class="mt-2 hidden">
                                    <x-text-input id="new_category" class="block w-full" type="text" placeholder="{{ __('སྡེ་ཚན་གསར་པ། (New Category)') }}" />
                                    <p class="text-sm text-gray-500 mt-1">{{ __('སྡེ་ཚན་གསར་པ་བྲིས་ནས་ཟིན་བྲིས་ཉར་ཚགས་བྱེད་དུས་རང་འགུལ་གྱིས་སྣོན་ངེས།') }}</p>
                                </div>
                                <x-input-error :messages="$errors->get('category')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="difficulty" :value="__('དཀའ་ཚད། (Difficulty)')" />
                                <select id="difficulty" name="difficulty" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                    <option value="1" {{ old('difficulty') == 1 ? 'selected' : '' }}>1 - {{ __('ལས་སླ།') }} (Easy)</option>
                                    <option value="2" {{ old('difficulty') == 2 ? 'selected' : '' }}>2 - {{ __('འབྲིང་ཙམ་ལས་སླ།') }} (Fairly Easy)</option>
                                    <option value="3" {{ old('difficulty') == 3 ? 'selected' : '' }}>3 - {{ __('འབྲིང་།') }} (Medium)</option>
                                    <option value="4" {{ old('difficulty') == 4 ? 'selected' : '' }}>4 - {{ __('འབྲིང་ཙམ་དཀའ་བ།') }} (Fairly Hard)</option>
                                    <option value="5" {{ old('difficulty') == 5 ? 'selected' : '' }}>5 - {{ __('དཀའ་བ།') }} (Hard)</option>
                                </select>
                                <x-input-error :messages="$errors->get('difficulty')" class="mt-2" />
                            </div>
                        </div>

                        <div class="mb-6">
                            <x-input-label for="tags" :value="__('དོན་ཚན། (Tags - select or add new)')" />
                            <div class="mt-1 relative">
                                <div class="flex flex-wrap gap-2 mb-2">
                                    @foreach($tags as $tag)
                                        <label class="inline-flex items-center bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded-md text-sm">
                                            <input type="checkbox" name="selected_tags[]" value="{{ $tag->name }}" class="mr-1">
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
                            >{{ old('context') }}</textarea>
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
                            >{{ old('reference') }}</textarea>
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
                            >{{ old('question') }}</textarea>
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
                            >{{ old('answer') }}</textarea>
                            <x-input-error :messages="$errors->get('answer')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ml-4">
                                {{ __('གསར་བཟོ།') }} (Create)
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
