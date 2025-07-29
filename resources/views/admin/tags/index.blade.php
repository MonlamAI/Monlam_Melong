<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Tag Management') }} / {{ __('ཚན་པ་རྩོམ་སྒྲིག') }}
            </h2>
            <a href="{{ route('entries.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                {{ __('Back to Entries') }}
            </a>
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

                    @if(session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    <!-- Add New Tag Form -->
                    <div class="mb-8 p-4 bg-gray-50 rounded-lg">
                        <h3 class="text-lg font-semibold mb-4">{{ __('Add New Tag') }} / {{ __('མཚོན་རྟགས་གསར་པ་སྣོན།') }}</h3>

                        <form action="{{ route('admin.tags.store') }}" method="POST">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <x-input-label for="name" :value="__('English Name')" />
                                    <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required />
                                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="tibetan_name" :value="__('Tibetan Name') . ' / ' . __('བོད་ཡིག་མིང་།')" />
                                    <x-text-input id="tibetan_name" class="block mt-1 w-full tibetan-font" type="text" name="tibetan_name" :value="old('tibetan_name')" />
                                    <x-input-error :messages="$errors->get('tibetan_name')" class="mt-2" />
                                </div>
                            </div>

                            <div class="mb-4">
                                <x-input-label for="description" :value="__('Description') . ' / ' . __('འགྲེལ་བཤད།')" />
                                <textarea id="description" name="description" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full" rows="2">{{ old('description') }}</textarea>
                                <x-input-error :messages="$errors->get('description')" class="mt-2" />
                            </div>

                            <div class="flex justify-end">
                                <x-primary-button>{{ __('Add Tag') }}</x-primary-button>
                            </div>
                        </form>
                    </div>

                    <!-- Tags List -->
                    <div>
                        <h3 class="text-lg font-semibold mb-4">{{ __('Existing Tags') }} / {{ __('ཡོད་བཞིན་པའི་མཚོན་རྟགས།') }}</h3>

                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="py-2 px-4 border-b text-left">{{ __('English Name') }}</th>
                                        <th class="py-2 px-4 border-b text-left">{{ __('Tibetan Name') }}</th>
                                        <th class="py-2 px-4 border-b text-left">{{ __('Description') }}</th>
                                        <th class="py-2 px-4 border-b text-center">

                                            <div>{{ __('Actions') }}</div>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($tags as $tag)
                                    <tr>
                                        <td class="py-2 px-4 border-b">{{ $tag->name }}</td>
                                        <td class="py-2 px-4 border-b tibetan-font">{{ !empty($tag->tibetan_name) ? $tag->tibetan_name : '-' }}</td>
                                        <td class="py-2 px-4 border-b tibetan-font">{{ !empty($tag->description) ? Str::limit($tag->description, 50) : '-' }}</td>
                                        <td class="py-2 px-4 border-b text-center">
                                            <div class="flex flex-row space-x-2 items-center justify-center">
                                                <button 
                                                    data-id="{{ $tag->id }}" 
                                                    data-name="{{ $tag->name }}" 
                                                    data-tibetan-name="{{ $tag->tibetan_name }}" 
                                                    data-description="{{ $tag->description }}"
                                                    class="edit-tag-btn text-indigo-600 hover:text-indigo-900">
                                                    <div class="flex items-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                                        </svg>
                                                        <span class="ml-1">{{ __('Edit') }}</span>
                                                    </div>
                                                </button>
                                                
                                                <form method="POST" action="{{ route('admin.tags.destroy', $tag->id) }}" 
                                                      onsubmit="return confirm('{{ __('Are you sure you want to delete this tag?') }}');" class="inline-block">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                        class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 shadow-sm transition-all duration-200">
                                                        <svg class="h-5 w-5 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                        <div>
                                                            <span class="tibetan-font font-bold block leading-none">{{ __('སུབ།') }}</span>
                                                        </div>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="py-4 px-4 border-b text-center text-gray-500">
                                            {{ __('No tags found.') }}
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Tag Modal -->
    <div id="editTagModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 items-center justify-center hidden" style="display: none;">
        <div class="bg-white p-6 rounded-lg shadow-lg max-w-md w-full">
            <h3 class="text-lg font-semibold mb-4">{{ __('Edit Tag') }} / {{ __('མཚོན་རྟགས་བཟོ་བཅོས།') }}</h3>

            <form id="editTagForm" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" id="tag_id" name="tag_id">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <x-input-label for="name" :value="__('English Name')" />
                        <x-text-input id="edit_name" class="block mt-1 w-full" type="text" name="name" required />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="tibetan_name" :value="__('Tibetan Name') . ' / ' . __('བོད་ཡིག་མིང་།')" />
                        <x-text-input id="edit_tibetan_name" class="block mt-1 w-full tibetan-font" type="text" name="tibetan_name" />
                        <x-input-error :messages="$errors->get('tibetan_name')" class="mt-2" />
                    </div>
                </div>

                <div class="mb-4">
                    <x-input-label for="edit_description" :value="__('Description') . ' / ' . __('འགྲེལ་བཤད།')" />
                    <textarea id="edit_description" name="description" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full" rows="2"></textarea>
                    <x-input-error :messages="$errors->get('description')" class="mt-2" />
                </div>

                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeEditModal()" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                        {{ __('དོར་བ།') }} / {{ __('Cancel') }}
                    </button>
                    <x-primary-button>{{ __('ཟིན་བྲིས་ཉར།') }} / {{ __('Update Tag') }}</x-primary-button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add event listeners to all edit buttons
            document.querySelectorAll('.edit-tag-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    const name = this.getAttribute('data-name');
                    const tibetanName = this.getAttribute('data-tibetan-name');
                    const description = this.getAttribute('data-description');
                    
                    document.getElementById('tag_id').value = id;
                    document.getElementById('edit_name').value = name;
                    document.getElementById('edit_tibetan_name').value = tibetanName || '';
                    document.getElementById('edit_description').value = description || '';

                    // Set form action with tag ID
                    document.getElementById('editTagForm').action = "{{ url('admin/tags') }}/" + id;
                    document.getElementById('editTagModal').classList.remove('hidden');
                    document.getElementById('editTagModal').style.display = 'flex';
                });
            });
        });

        function closeEditModal() {
            document.getElementById('editTagModal').classList.add('hidden');
            document.getElementById('editTagModal').style.display = 'none';
        }
    </script>
</x-app-layout>
