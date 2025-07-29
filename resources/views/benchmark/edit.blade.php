<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('ཚད་འཇལ་དྲི་བ་རྩོམ་སྒྲིག') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('benchmark.update', $benchmark->id) }}">
                        @csrf
                        @method('PUT')

                        <!-- Subject -->
                        <div class="mb-4">
                            <x-input-label for="subject" :value="__('བརྗོད་གཞི།')" />
                            <x-text-input id="subject" class="block mt-1 w-full" type="text" name="subject" :value="old('subject', $benchmark->subject)" required autofocus />
                            <x-input-error :messages="$errors->get('subject')" class="mt-2" />
                        </div>

                        <!-- Question Type -->
                        <div class="mb-4">
                            <x-input-label for="question_type" :value="__('དྲི་བའི་རིགས།')" />
                            <select id="question_type" name="question_type" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full">
                                @foreach($questionTypes as $value => $label)
                                    <option value="{{ $value }}" {{ old('question_type', $benchmark->question_type) == $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('question_type')" class="mt-2" />
                        </div>

                        <!-- Question Text -->
                        <div class="mb-4">
                            <x-input-label for="question_text" :value="__('དྲི་བ།')" />
                            <textarea id="question_text" name="question_text" rows="4" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full" required>{{ old('question_text', $benchmark->question_text) }}</textarea>
                            <x-input-error :messages="$errors->get('question_text')" class="mt-2" />
                        </div>

                        <!-- Answer Options -->
                        <div class="mb-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="answer_option1" :value="__('ལན་འདེབས། ༡')" />
                                <textarea id="answer_option1" name="answer_option1" rows="2" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full" required>{{ old('answer_option1', $benchmark->answer_option1) }}</textarea>
                                <x-input-error :messages="$errors->get('answer_option1')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="answer_option2" :value="__('ལན་འདེབས། ༢')" />
                                <textarea id="answer_option2" name="answer_option2" rows="2" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full" required>{{ old('answer_option2', $benchmark->answer_option2) }}</textarea>
                                <x-input-error :messages="$errors->get('answer_option2')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="answer_option3" :value="__('ལན་འདེབས། ༣')" />
                                <textarea id="answer_option3" name="answer_option3" rows="2" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full" required>{{ old('answer_option3', $benchmark->answer_option3) }}</textarea>
                                <x-input-error :messages="$errors->get('answer_option3')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="answer_option4" :value="__('ལན་འདེབས། ༤')" />
                                <textarea id="answer_option4" name="answer_option4" rows="2" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full" required>{{ old('answer_option4', $benchmark->answer_option4) }}</textarea>
                                <x-input-error :messages="$errors->get('answer_option4')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Correct Answer -->
                        <div class="mb-4">
                            <x-input-label for="correct_answer" :value="__('ལན་ངོ་མ།')" />
                            <textarea id="correct_answer" name="correct_answer" rows="2" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full" required>{{ old('correct_answer', $benchmark->correct_answer) }}</textarea>
                            <x-input-error :messages="$errors->get('correct_answer')" class="mt-2" />
                            <p class="text-sm text-gray-600 mt-1">{{ __('ལན་འདེབས། བཞིའི་ནང་ནས་གང་རུང་ཞིག་འདི་ལ་བཀོད་དགོས།') }}</p>
                        </div>

                        <!-- Explanation -->
                        <div class="mb-4">
                            <x-input-label for="explanation" :value="__('འགྲེལ་བཤད།')" />
                            <textarea id="explanation" name="explanation" rows="4" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full">{{ old('explanation', $benchmark->explanation) }}</textarea>
                            <x-input-error :messages="$errors->get('explanation')" class="mt-2" />
                        </div>

                        <!-- Difficulty Level -->
                        <div class="mb-4">
                            <x-input-label for="difficulty_level" :value="__('དཀའ་ཚད།')" />
                            <select id="difficulty_level" name="difficulty_level" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full">
                                @foreach($difficultyLevels as $level)
                                    <option value="{{ $level }}" {{ old('difficulty_level', $benchmark->difficulty_level) == $level ? 'selected' : '' }}>
                                        {{ ucfirst($level) }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('difficulty_level')" class="mt-2" />
                        </div>

                        <!-- Category -->
                        <div class="mb-4">
                            <x-input-label for="category" :value="__('སྡེ་ཚན།')" />
                            <x-text-input id="category" class="block mt-1 w-full" type="text" name="category" :value="old('category', $benchmark->category)" />
                            <x-input-error :messages="$errors->get('category')" class="mt-2" />
                        </div>

                        <!-- Tags -->
                        <div class="mb-4">
                            <x-input-label for="tags" :value="__('ངོ་རྟགས།')" />
                            <x-text-input id="tags" class="block mt-1 w-full" type="text" name="tags" :value="old('tags', $benchmark->tags)" />
                            <x-input-error :messages="$errors->get('tags')" class="mt-2" />
                            <p class="text-sm text-gray-600 mt-1">{{ __('ཚིག་གྲུབ་མང་པོ་ཡོད་ན་ཚེག་ཁྱིམ་ , བཞག་སྟེ་བྲིས།') }}</p>
                        </div>

                        <!-- Is Active -->
                        <div class="mb-4">
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $benchmark->is_active) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <span class="ml-2">{{ __('འགྲོ་མུས།') }}</span>
                            </label>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('benchmark.show', $benchmark->id) }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 mr-2">
                                {{ __('ཕྱིར་ལོག') }}
                            </a>
                            <x-primary-button type="submit">
                                {{ __('བཟོ་བཅོས་ཉར།') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
