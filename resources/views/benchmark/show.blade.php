<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('ཚད་འཇལ་དྲི་བ་སྟོན་པ།') }}
            </h2>
            <div>
                <a href="{{ route('benchmark.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    {{ __('ཕྱིར་ལོག') }}
                </a>
                @if (Auth::check() && Auth::user()->isAdmin())
                    <a href="{{ route('benchmark.edit', $benchmark->id) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded ml-2">
                        {{ __('རྩོམ་སྒྲིག') }}
                    </a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif
                    
                    <div class="mb-6 pb-4 border-b">
                        <div class="flex justify-between items-center">
                            <h3 class="text-lg font-medium mb-2">{{ __('བརྗོད་གཞི།') }}: {{ $benchmark->subject }}</h3>
                            <div>
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if($benchmark->difficulty_level == 'easy') bg-emerald-100 text-emerald-800 
                                    @elseif($benchmark->difficulty_level == 'medium') bg-amber-100 text-amber-800 
                                    @elseif($benchmark->difficulty_level == 'hard') bg-orange-200 text-orange-900 
                                    @elseif($benchmark->difficulty_level == 'expert') bg-rose-100 text-rose-800 
                                    @endif">
                                    {{ __('དཀའ་ཚད།') }}: {{ ucfirst($benchmark->difficulty_level) }}
                                </span>
                                
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full ml-2
                                    {{ $benchmark->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $benchmark->is_active ? __('འགྲོ་མུས།') : __('འགྲོ་མེད་པ།') }}
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-6 mb-4">
                        <h3 class="text-lg font-medium mb-2">{{ __('དྲི་བ།') }}:</h3>
                        <div class="bg-gray-50 p-4 rounded">
                            <p class="text-gray-900">{{ $benchmark->question_text }}</p>
                        </div>
                    </div>
                    
                    <div class="mt-6 mb-4">
                        <h3 class="text-lg font-medium mb-2">{{ __('ལན་འདེམས་ཀ།') }}:</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-gray-50 p-4 rounded {{ $benchmark->correct_answer == $benchmark->answer_option1 ? 'border-2 border-green-500' : '' }}">
                                <span class="font-semibold">1:</span> {{ $benchmark->answer_option1 }}
                                @if($benchmark->correct_answer == $benchmark->answer_option1)
                                    <span class="ml-2 text-green-600">✓</span>
                                @endif
                            </div>
                            <div class="bg-gray-50 p-4 rounded {{ $benchmark->correct_answer == $benchmark->answer_option2 ? 'border-2 border-green-500' : '' }}">
                                <span class="font-semibold">2:</span> {{ $benchmark->answer_option2 }}
                                @if($benchmark->correct_answer == $benchmark->answer_option2)
                                    <span class="ml-2 text-green-600">✓</span>
                                @endif
                            </div>
                            <div class="bg-gray-50 p-4 rounded {{ $benchmark->correct_answer == $benchmark->answer_option3 ? 'border-2 border-green-500' : '' }}">
                                <span class="font-semibold">3:</span> {{ $benchmark->answer_option3 }}
                                @if($benchmark->correct_answer == $benchmark->answer_option3)
                                    <span class="ml-2 text-green-600">✓</span>
                                @endif
                            </div>
                            <div class="bg-gray-50 p-4 rounded {{ $benchmark->correct_answer == $benchmark->answer_option4 ? 'border-2 border-green-500' : '' }}">
                                <span class="font-semibold">4:</span> {{ $benchmark->answer_option4 }}
                                @if($benchmark->correct_answer == $benchmark->answer_option4)
                                    <span class="ml-2 text-green-600">✓</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-6 mb-4">
                        <h3 class="text-lg font-medium mb-2">{{ __('ལན་ངོ་མ།') }}:</h3>
                        <div class="bg-green-50 p-4 rounded">
                            <p class="text-gray-900">{{ $benchmark->correct_answer }}</p>
                        </div>
                    </div>
                    
                    @if($benchmark->explanation)
                        <div class="mt-6 mb-4">
                            <h3 class="text-lg font-medium mb-2">{{ __('འགྲེལ་བཤད།') }}:</h3>
                            <div class="bg-blue-50 p-4 rounded">
                                <p class="text-gray-900">{{ $benchmark->explanation }}</p>
                            </div>
                        </div>
                    @endif
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        @if($benchmark->category)
                            <div class="mt-6 mb-4">
                                <h3 class="text-lg font-medium mb-2">{{ __('སྡེ་ཚན།') }}:</h3>
                                <p class="text-gray-700">{{ $benchmark->category }}</p>
                            </div>
                        @endif
                        
                        @if($benchmark->tags)
                            <div class="mt-6 mb-4">
                                <h3 class="text-lg font-medium mb-2">{{ __('ངོ་རྟགས།') }}:</h3>
                                <div class="flex flex-wrap gap-1">
                                    @foreach(explode(',', $benchmark->tags) as $tag)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                            {{ trim($tag) }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                    
                    <div class="border-t pt-4 text-sm text-gray-600">
                        <p>{{ __('བཟོ་མཁན།') }} / Created by: {{ $benchmark->created_by ?? 'System' }}</p>
                        <p>{{ __('བཟོས་དུས།') }} / Created: {{ $benchmark->created_at->format('Y-m-d H:i:s') }}</p>
                        @if($benchmark->updated_by)
                            <p>{{ __('བཟོ་བཅོས་རྒྱག་མཁན།') }} / Last updated by: {{ $benchmark->updated_by }}</p>
                        @endif
                        <p>{{ __('བཟོ་བཅོས་བྱས་དུས།') }} / Last updated: {{ $benchmark->updated_at->format('Y-m-d H:i:s') }}</p>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
