<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('སྡེ་ཚན་དོ་དམ།') }} / {{ __('Category Management') }}
            </h2>
            <a href="{{ route('entries.index') }}" class="bg-indigo-600 hover:bg-indigo-800 text-white font-medium py-2 px-4 rounded-md transition duration-150 ease-in-out">
                {{ __('ཕྱིར་ལོག') }} / {{ __('Back to Entries') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Success Message -->
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-md relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif
            
            <!-- Error Message -->
            @if(session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-md relative" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <!-- Add New Category Form -->
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">
                        {{ __('སྡེ་ཚན་གསར་པ་སྣོན།') }} / {{ __('Add New Category') }}
                    </h3>
                    
                    <form action="{{ route('admin.categories.store') }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <x-input-label for="name" :value="__('English Name')" class="font-medium" />
                                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="tibetan_name" :value="__('Tibetan Name') . ' / ' . __('བོད་ཡིག་མིང་།')" class="font-medium" />
                                <x-text-input id="tibetan_name" class="block mt-1 w-full tibetan-font" type="text" name="tibetan_name" :value="old('tibetan_name')" />
                                <x-input-error :messages="$errors->get('tibetan_name')" class="mt-2" />
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <x-input-label for="description" :value="__('Description') . ' / ' . __('འགྲེལ་བཤད།')" class="font-medium" />
                            <textarea id="description" name="description" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full" rows="2">{{ old('description') }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>
                        
                        <div class="flex justify-end">
                            <x-primary-button>
                                <span class="mr-1">+</span> {{ __('སྣོན།') }} / {{ __('Add') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>

                <!-- Categories List -->
                <div class="p-6 bg-white dark:bg-gray-800">
                    <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">
                        {{ __('ཡོད་བཞིན་པའི་སྡེ་ཚན།') }} / {{ __('Existing Categories') }}
                    </h3>
                    
                    <div class="overflow-x-auto rounded-md border border-gray-200 dark:border-gray-700">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        {{ __('English Name') }}
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        {{ __('Tibetan Name') }}
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        {{ __('Description') }}
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        {{ __('བཀོལ་སྤྱོད།') }} / {{ __('Actions') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($categories as $category)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ $category->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100 tibetan-font">
                                        {{ !empty($category->tibetan_name) ? $category->tibetan_name : '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100 tibetan-font">
                                        {{ !empty($category->description) ? Str::limit($category->description, 50) : '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right space-x-2">
                                        <button 
                                            onclick="editCategory('{{ urlencode(json_encode($category)) }}')" 
                                            class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 inline-flex items-center">
                                            <svg class="h-5 w-5 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            {{ __('བཟོ་བཅོས།') }} / {{ __('Edit') }}
                                        </button>
                                        <form class="inline-block" method="POST" action="{{ route('admin.categories.destroy', $category->id) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button 
                                                type="button"
                                                onclick="confirmDelete(this)"
                                                class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 inline-flex items-center">
                                                <svg class="h-5 w-5 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                                {{ __('སུབ།') }} / {{ __('Delete') }}
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="2" class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500 dark:text-gray-400">
                                        {{ __('སྡེ་ཚན་མི་འདུག') }} / {{ __('No categories found.') }}
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

    <!-- Edit Category Modal -->
    <div id="editCategoryModal" class="fixed inset-0 bg-gray-600 bg-opacity-70 hidden z-50" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                        {{ __('སྡེ་ཚན་བཟོ་བཅོས།') }} / {{ __('Edit Category') }}
                    </h3>
                    <button type="button" onclick="closeEditModal()" class="text-gray-400 hover:text-gray-500">
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <form id="editCategoryForm" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="category_id" name="category_id">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="edit_name" :value="__('English Name')" class="font-medium" />
                            <x-text-input id="edit_name" class="block mt-1 w-full" type="text" name="name" required />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="edit_tibetan_name" :value="__('Tibetan Name') . ' / ' . __('བོད་ཡིག་མིང་།')" class="font-medium" />
                            <x-text-input id="edit_tibetan_name" class="block mt-1 w-full tibetan-font" type="text" name="tibetan_name" />
                            <x-input-error :messages="$errors->get('tibetan_name')" class="mt-2" />
                        </div>
                    </div>
                    
                    <div>
                        <x-input-label for="edit_description" :value="__('Description') . ' / ' . __('འགྲེལ་བཤད།')" class="font-medium" />
                        <textarea id="edit_description" name="description" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full" rows="2"></textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>
                    
                    <div class="flex justify-end pt-4 space-x-3">
                        <button type="button" onclick="closeEditModal()" class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-800 dark:text-gray-200 rounded-md hover:bg-gray-400 dark:hover:bg-gray-500 transition">
                            {{ __('དོར།') }} / {{ __('Cancel') }}
                        </button>
                        <x-primary-button>
                            {{ __('ཉར།') }} / {{ __('Update') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteConfirmModal" class="fixed inset-0 bg-gray-600 bg-opacity-70 hidden z-50" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full p-6">
                <div class="text-center">
                    <svg class="mx-auto h-12 w-12 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mt-4">
                        {{ __('སྡེ་ཚན་འདི་སུབ་རྒྱུ་གཏན་འཁེལ་ལམ།') }}
                    </h3>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                        {{ __('སྡེ་ཚན་སུབ་ཚར་ན་བསྐྱར་གསོ་བྱེད་མི་ཐུབ།') }}
                    </p>
                </div>
                
                <div class="mt-5 flex justify-center space-x-3">
                    <button id="cancelDelete" type="button" class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-800 dark:text-gray-200 rounded-md hover:bg-gray-400 dark:hover:bg-gray-500 transition">
                        {{ __('དོར།') }} / {{ __('Cancel') }}
                    </button>
                    <button id="confirmDelete" type="button" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition">
                        {{ __('སུབ།') }} / {{ __('Delete') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // For editing a category
        function editCategory(encodedCategory) {
            try {
                const categoryData = JSON.parse(decodeURIComponent(encodedCategory));
                document.getElementById('category_id').value = categoryData.id;
                document.getElementById('edit_name').value = categoryData.name;
                document.getElementById('edit_tibetan_name').value = categoryData.tibetan_name || '';
                document.getElementById('edit_description').value = categoryData.description || '';
                document.getElementById('editCategoryForm').action = "{{ route('admin.categories.update', '_PLACEHOLDER_') }}".replace('_PLACEHOLDER_', categoryData.id);
                document.getElementById('editCategoryModal').style.display = 'flex';
            } catch (error) {
                console.error('Error parsing category data:', error);
                alert('An error occurred while trying to edit this category.');
            }
        }
        
        function closeEditModal() {
            document.getElementById('editCategoryModal').style.display = 'none';
        }

        // For deleting a category with confirmation
        let deleteForm = null;
        
        function confirmDelete(button) {
            deleteForm = button.closest('form');
            document.getElementById('deleteConfirmModal').style.display = 'flex';
        }
        
        document.getElementById('cancelDelete').addEventListener('click', function() {
            document.getElementById('deleteConfirmModal').style.display = 'none';
            deleteForm = null;
        });
        
        document.getElementById('confirmDelete').addEventListener('click', function() {
            if (deleteForm) {
                deleteForm.submit();
            }
        });
        
        // Close modals when clicking outside
        window.addEventListener('click', function(event) {
            const editModal = document.getElementById('editCategoryModal');
            const deleteModal = document.getElementById('deleteConfirmModal');
            
            if (event.target === editModal) {
                editModal.style.display = 'none';
            }
            
            if (event.target === deleteModal) {
                deleteModal.style.display = 'none';
                deleteForm = null;
            }
        });
    </script>
</x-app-layout>
