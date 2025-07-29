<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('སྨོན་ལམ་རིག་ནུས་ཚད་འཇལ།') }}
            </h2>
            @if(Auth::check() && Auth::user()->canManageBenchmarks())
                <a href="{{ route('benchmark.create') }}" class="bg-indigo-600 hover:bg-indigo-800 text-white font-bold py-2 px-4 rounded border-2 border-indigo-800 shadow-md">
                    {{ __('གསར་སྣོན།') }}
                </a>
            @endif

        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    @if(session('success'))
                        <div class="bg-emerald-100 border border-emerald-400 text-emerald-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    @if($benchmarks->isEmpty())
                        <p class="text-center py-4 text-gray-600">{{ __('ཚད་འཇལ་གྱི་དྲི་བ་མི་འདུག') }}</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="px-6 py-3 border-b border-gray-200 text-left text-xs leading-4 font-medium text-gray-600 uppercase tracking-wider">
                                            {{ __('བརྗོད་གཞི།') }}
                                        </th>
                                        <th class="px-6 py-3 border-b border-gray-200 text-left text-xs leading-4 font-medium text-gray-600 uppercase tracking-wider">
                                            {{ __('དྲི་བ།') }}
                                        </th>
                                        <th class="px-6 py-3 border-b border-gray-200 text-left text-xs leading-4 font-medium text-gray-600 uppercase tracking-wider">
                                            {{ __('དཀའ་ཚད།') }}
                                        </th>
                                        <th class="px-6 py-3 border-b border-gray-200 text-left text-xs leading-4 font-medium text-gray-600 uppercase tracking-wider">
                                            {{ __('གནས་སྟངས།') }}
                                        </th>
                                        <th class="px-6 py-3 border-b border-gray-200 text-center text-xs leading-4 font-medium text-gray-600 uppercase tracking-wider">
                                            {{ __('བྱེད་སྒོ།') }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white">
                                    @foreach($benchmarks as $benchmark)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap border-b border-gray-200">
                                                <div class="text-sm leading-5 font-medium text-gray-900">
                                                    {{ $benchmark->subject }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 border-b border-gray-200">
                                                <div class="text-sm leading-5 text-gray-900 whitespace-normal">
                                                    {{ \Illuminate\Support\Str::limit($benchmark->question_text, 100) }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap border-b border-gray-200">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                    @if($benchmark->difficulty_level == 'easy') bg-emerald-100 text-emerald-800
                                                    @elseif($benchmark->difficulty_level == 'medium') bg-amber-100 text-amber-800
                                                    @elseif($benchmark->difficulty_level == 'hard') bg-orange-200 text-orange-900
                                                    @elseif($benchmark->difficulty_level == 'expert') bg-rose-100 text-rose-800
                                                    @endif">
                                                    {{ ucfirst($benchmark->difficulty_level) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap border-b border-gray-200">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                    {{ $benchmark->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-800' }}">
                                                    {{ $benchmark->is_active ? __('འགྲོ་བཞིན་པ།') : __('འགྲོ་མེད་པ།') }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap border-b border-gray-200 text-center">
                                                <div class="flex justify-center space-x-2">
                                                    <a href="{{ route('benchmark.show', $benchmark->id) }}" class="text-blue-600 hover:text-blue-900 mr-2">
                                                        <i class="fas fa-eye"></i> {{ __('ལྟ་བ།') }}
                                                    </a>

                                                    @if(Auth::check() && Auth::user()->canManageBenchmarks())
                                                        <a href="{{ route('benchmark.edit', $benchmark->id) }}" class="text-green-600 hover:text-green-900 mr-2">
                                                            <i class="fas fa-edit"></i> {{ __('རྩོམ་སྒྲིག') }}
                                                        </a>

                                                        <form action="{{ route('benchmark.destroy', $benchmark->id) }}" method="POST" class="inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('{{ __('ཁྱེད་ཀྱིས་དྲི་བ་འདི་གཙང་སེལ་བྱེད་རྒྱུ་གཏན་འཁེལ་ལམ?') }} / Are you sure you want to delete this question?');">
                                                                <i class="fas fa-trash"></i> {{ __('དོར་བ།') }}
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            {{ $benchmarks->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
