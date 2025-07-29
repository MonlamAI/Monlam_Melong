<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('སྤྱོད་མི་བཟོ་བཅོས།') }} (Edit User)
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('admin.users.update', $user) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('མིང་།') }} (Name)</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}"
                                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md">
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('གློག་འཕྲིན།') }} (Email)</label>
                            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md">
                            @error('email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="role" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('འགན་འཛིན།') }} (Role)</label>
                            <select name="role" id="role"
                                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md">
                                <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>{{ __('འཛིན་སྐྱོང་པ།') }} (Admin)</option>
                                <option value="chief_editor" {{ old('role', $user->role) === 'chief_editor' ? 'selected' : '' }}>{{ __('རྩོམ་སྒྲིག་འགན་འཛིན།') }} (Chief Editor)</option>
                                <option value="editor" {{ old('role', $user->role) === 'editor' ? 'selected' : '' }}>{{ __('རྩོམ་སྒྲིག་པ།') }} (Editor)</option>
                                <option value="benchmark_editor" {{ old('role', $user->role) === 'benchmark_editor' ? 'selected' : '' }}>{{ __('ཚད་འཇལ་རྩོམ་སྒྲིག་པ།') }} (Benchmark Editor)</option>
                                <option value="reviewer" {{ old('role', $user->role) === 'reviewer' ? 'selected' : '' }}>{{ __('ཞུ་དག་པ།') }} (Reviewer)</option>
                            </select>
                            @error('role')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password Update (Optional) -->
                        <div class="mb-4">
                            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('གསང་ཚིག་གསར་པ།') }} (New Password - Leave blank to keep current)</label>
                            <input type="password" name="password" id="password"
                                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md">
                            @error('password')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('གསང་ཚིག་གཏན་འཁེལ།') }} (Confirm Password)</label>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md">
                        </div>

                        <!-- Permissions Matrix Section -->
                        <div class="mb-8">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4 border-b pb-2">
                                {{ __('དབང་ཚད་བཀོད་སྒྲིག') }} (Permission Matrix)
                            </h3>

                            <!-- Category Permissions -->
                            <div class="mb-6">
                                <label class="block text-lg font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    {{ __('༡ སྡེ་ཚན་དབང་ཚད།') }} (1. Category Permissions)
                                </label>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">
                                    {{ __('སྤྱོད་མི་འདིས་བལྟ་བཟོ་བྱེད་ཆོག་པའི་སྡེ་ཚན་གདམ་ག') }}
                                    (Select categories this user is allowed to access. Leave all unchecked for access to all categories.)
                                </p>

                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                    @foreach($categories as $category)
                                        <div class="flex items-center">
                                             <input type="checkbox" name="allowed_categories[]" id="category-{{ $loop->index }}" value="{{ $category->name }}"
                                                {{ (is_array(old('allowed_categories', $user->allowed_categories ?? [])) && in_array($category->name, old('allowed_categories', $user->allowed_categories ?? []))) ? 'checked' : '' }}
                                                {{ ($user->role === 'admin' || (isset($user->allowed_categories) && is_array($user->allowed_categories) && in_array('ཡོངས་རྫོགས།', $user->allowed_categories))) ? 'checked' : '' }}
                                                class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded category-checkbox">
                                            <label for="category-{{ $loop->index }}" class="ml-2 block text-sm text-gray-900 dark:text-gray-300">
                                                {{ $category->tibetan_name ?: $category->name }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Feature Permissions Matrix -->
                            <div class="mb-6">
                                <label class="block text-lg font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    {{ __('༢ ནང་དོན་བཀོད་སྒྲིག་གི་དབང་ཚད།') }} (2. Content Management Permissions)
                                </label>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 border dark:border-gray-700">
                                        <thead class="bg-gray-50 dark:bg-gray-800">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider border-r dark:border-gray-700">
                                                    {{ __('ནང་དོན།') }} (Features)
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                    {{ __('གསར་སྣོན།') }} (Create)
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                    {{ __('བཟོ་བཅོས།') }} (Edit)
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                    {{ __('དོར་བ།') }} (Delete)
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                    {{ __('བལྟ་བ།') }} (View)
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                                            <!-- Row: Entry Review/Submit -->
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100 border-r dark:border-gray-700">
                                                    {{ __('བསྐྱར་ཞིབ་དགོས་པའི་ནང་དོན།') }} (Content for Review)
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                                    <input type="checkbox" name="permissions[review][create]" id="perm-review-create" value="1"
                                                        {{ (isset($user->permissions['review']['create']) && $user->permissions['review']['create'] == 1) ? 'checked' : '' }}
                                                        class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                                    <input type="checkbox" name="permissions[review][edit]" id="perm-review-edit" value="1"
                                                        {{ (isset($user->permissions['review']['edit']) && $user->permissions['review']['edit'] == 1) ? 'checked' : '' }}
                                                        class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                                    <input type="checkbox" name="permissions[review][delete]" id="perm-review-delete" value="1"
                                                        {{ (isset($user->permissions['review']['delete']) && $user->permissions['review']['delete'] == 1) ? 'checked' : '' }}
                                                        class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                                    <input type="checkbox" name="permissions[review][view]" id="perm-review-view" value="1"
                                                        {{ (isset($user->permissions['review']['view']) && $user->permissions['review']['view'] == 1) ? 'checked' : '' }}
                                                        class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                                </td>
                                            </tr>

                                            <!-- Row: User Management -->
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100 border-r dark:border-gray-700">
                                                    {{ __('ལས་མི་དོ་དམ།') }} (User Management)
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                                    <input type="checkbox" name="permissions[users][create]" id="perm-users-create" value="1"
                                                        {{ (isset($user->permissions['users']['create']) && $user->permissions['users']['create'] == 1) ? 'checked' : '' }}
                                                        class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                                    <input type="checkbox" name="permissions[users][edit]" id="perm-users-edit" value="1"
                                                        {{ (isset($user->permissions['users']['edit']) && $user->permissions['users']['edit'] == 1) ? 'checked' : '' }}
                                                        class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                                    <input type="checkbox" name="permissions[users][delete]" id="perm-users-delete" value="1"
                                                        {{ (isset($user->permissions['users']['delete']) && $user->permissions['users']['delete'] == 1) ? 'checked' : '' }}
                                                        class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                                    <input type="checkbox" name="permissions[users][view]" id="perm-users-view" value="1"
                                                        {{ (isset($user->permissions['users']['view']) && $user->permissions['users']['view'] == 1) ? 'checked' : '' }}
                                                        class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                                </td>
                                            </tr>

                                            <!-- Row: Category Management -->
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100 border-r dark:border-gray-700">
                                                    {{ __('སྡེ་ཚན་དོ་དམ།') }} (Category Management)
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                                    <input type="checkbox" name="permissions[categories][create]" id="perm-categories-create" value="1"
                                                        {{ (isset($user->permissions['categories']['create']) && $user->permissions['categories']['create'] == 1) ? 'checked' : '' }}
                                                        class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                                    <input type="checkbox" name="permissions[categories][edit]" id="perm-categories-edit" value="1"
                                                        {{ (isset($user->permissions['categories']['edit']) && $user->permissions['categories']['edit'] == 1) ? 'checked' : '' }}
                                                        class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                                    <input type="checkbox" name="permissions[categories][delete]" id="perm-categories-delete" value="1"
                                                        {{ (isset($user->permissions['categories']['delete']) && $user->permissions['categories']['delete'] == 1) ? 'checked' : '' }}
                                                        class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                                    <input type="checkbox" name="permissions[categories][view]" id="perm-categories-view" value="1"
                                                        {{ (isset($user->permissions['categories']['view']) && $user->permissions['categories']['view'] == 1) ? 'checked' : '' }}
                                                        class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                                </td>
                                            </tr>

                                            <!-- Row: Tag Management -->
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100 border-r dark:border-gray-700">
                                                    {{ __('ཚན་པ་དོ་དམ།') }} (Tag Management)
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                                    <input type="checkbox" name="permissions[Tags][create]" id="perm-Tags-create" value="1"
                                                        {{ (isset($user->permissions['Tags']['create']) && $user->permissions['Tags']['create'] == 1) ? 'checked' : '' }}
                                                        class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                                    <input type="checkbox" name="permissions[Tags][edit]" id="perm-Tags-edit" value="1"
                                                        {{ (isset($user->permissions['Tags']['edit']) && $user->permissions['Tags']['edit'] == 1) ? 'checked' : '' }}
                                                        class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                                    <input type="checkbox" name="permissions[Tags][delete]" id="perm-Tags-delete" value="1"
                                                        {{ (isset($user->permissions['Tags']['delete']) && $user->permissions['Tags']['delete'] == 1) ? 'checked' : '' }}
                                                        class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                                    <input type="checkbox" name="permissions[Tags][view]" id="perm-Tags-view" value="1"
                                                        {{ (isset($user->permissions['Tags']['view']) && $user->permissions['Tags']['view'] == 1) ? 'checked' : '' }}
                                                        class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                                </td>
                                            </tr>

                                            <!-- Row: Own Benchmark Questions -->
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100 border-r dark:border-gray-700">
                                                    {{ __('ཚད་འཇལ་དྲི་བ།-རང་ཉིད་ཁོ་ན།') }} (Own Benchmark Questions Only)
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                                    <input type="checkbox" name="permissions[own_benchmark][create]" id="perm-own-benchmark-create" value="1"
                                                        {{ (isset($user->permissions['own_benchmark']['create']) && $user->permissions['own_benchmark']['create'] == 1) ? 'checked' : '' }}
                                                        class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                                    <input type="checkbox" name="permissions[own_benchmark][edit]" id="perm-own-benchmark-edit" value="1"
                                                        {{ (isset($user->permissions['own_benchmark']['edit']) && $user->permissions['own_benchmark']['edit'] == 1) ? 'checked' : '' }}
                                                        class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                                    <input type="checkbox" name="permissions[own_benchmark][delete]" id="perm-own-benchmark-delete" value="1"
                                                        {{ (isset($user->permissions['own_benchmark']['delete']) && $user->permissions['own_benchmark']['delete'] == 1) ? 'checked' : '' }}
                                                        class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                                    <input type="checkbox" name="permissions[own_benchmark][view]" id="perm-own-benchmark-view" value="1"
                                                        {{ (isset($user->permissions['own_benchmark']['view']) && $user->permissions['own_benchmark']['view'] == 1) ? 'checked' : '' }}
                                                        class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                                </td>
                                            </tr>

                                            <!-- Row: All Editors' Benchmark Questions -->
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100 border-r dark:border-gray-700">
                                                    {{ __('ཚད་འཇལ་དྲི་བ།-རྩོམ་སྒྲིག་པ་ཡོངས།') }} (All Editors' Benchmark Questions)
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                                    <input type="checkbox" name="permissions[all_benchmark][create]" id="perm-all-benchmark-create" value="1"
                                                        {{ (isset($user->permissions['all_benchmark']['create']) && $user->permissions['all_benchmark']['create'] == 1) ? 'checked' : '' }}
                                                        class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                                    <input type="checkbox" name="permissions[all_benchmark][edit]" id="perm-all-benchmark-edit" value="1"
                                                        {{ (isset($user->permissions['all_benchmark']['edit']) && $user->permissions['all_benchmark']['edit'] == 1) ? 'checked' : '' }}
                                                        class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                                    <input type="checkbox" name="permissions[all_benchmark][delete]" id="perm-all-benchmark-delete" value="1"
                                                        {{ (isset($user->permissions['all_benchmark']['delete']) && $user->permissions['all_benchmark']['delete'] == 1) ? 'checked' : '' }}
                                                        class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                                    <input type="checkbox" name="permissions[all_benchmark][view]" id="perm-all-benchmark-view" value="1"
                                                        {{ (isset($user->permissions['all_benchmark']['view']) && $user->permissions['all_benchmark']['view'] == 1) ? 'checked' : '' }}
                                                        class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                                </td>
                                            </tr>

                                            <!-- Row: Own Submitted Entries -->
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100 border-r dark:border-gray-700">
                                                    {{ __('ནང་འཇུག་ཟིན་པ།-རང་ཉིད་ཁོ་ན།') }} (Own Submitted Entries Only)
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                                    <input type="checkbox" name="permissions[own_submitted][create]" id="perm-own-submitted-create" value="1"
                                                        {{ (isset($user->permissions['own_submitted']['create']) && $user->permissions['own_submitted']['create'] == 1) ? 'checked' : '' }}
                                                        class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                                    <input type="checkbox" name="permissions[own_submitted][edit]" id="perm-own-submitted-edit" value="1"
                                                        {{ (isset($user->permissions['own_submitted']['edit']) && $user->permissions['own_submitted']['edit'] == 1) ? 'checked' : '' }}
                                                        class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                                    <input type="checkbox" name="permissions[own_submitted][delete]" id="perm-own-submitted-delete" value="1"
                                                        {{ (isset($user->permissions['own_submitted']['delete']) && $user->permissions['own_submitted']['delete'] == 1) ? 'checked' : '' }}
                                                        class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                                    <input type="checkbox" name="permissions[own_submitted][view]" id="perm-own-submitted-view" value="1"
                                                        {{ (isset($user->permissions['own_submitted']['view']) && $user->permissions['own_submitted']['view'] == 1) ? 'checked' : '' }}
                                                        class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                                </td>
                                            </tr>

                                            <!-- Row: All Editors' Submitted Entries -->
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100 border-r dark:border-gray-700">
                                                    {{ __('ནང་འཇུག་ཟིན་པ།-རྩོམ་སྒྲིག་པ་ཡོངས།') }} (All Editors' Submitted Entries)
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                                    <input type="checkbox" name="permissions[all_submitted][create]" id="perm-all-submitted-create" value="1"
                                                        {{ (isset($user->permissions['all_submitted']['create']) && $user->permissions['all_submitted']['create'] == 1) ? 'checked' : '' }}
                                                        class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                                    <input type="checkbox" name="permissions[all_submitted][edit]" id="perm-all-submitted-edit" value="1"
                                                        {{ (isset($user->permissions['all_submitted']['edit']) && $user->permissions['all_submitted']['edit'] == 1) ? 'checked' : '' }}
                                                        class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                                    <input type="checkbox" name="permissions[all_submitted][delete]" id="perm-all-submitted-delete" value="1"
                                                        {{ (isset($user->permissions['all_submitted']['delete']) && $user->permissions['all_submitted']['delete'] == 1) ? 'checked' : '' }}
                                                        class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                                    <input type="checkbox" name="permissions[all_submitted][view]" id="perm-all-submitted-view" value="1"
                                                        {{ (isset($user->permissions['all_submitted']['view']) && $user->permissions['all_submitted']['view'] == 1) ? 'checked' : '' }}
                                                        class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                                </td>
                                            </tr>

                                            <!-- Add more rows as needed for other permissions -->
                                        </tbody>
                                    </table>
                                </div>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                                    {{ __('མཐའ་མའི་དབང་ཚད་རྣམས་སྤྱོད་མིའི་འགན་འཛིན་ལ་གཞིགས་ནས་ཐག་གཅོད་བྱེད་ངེས།') }}
                                    (Effective permissions will be determined by a combination of role and these custom settings)
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center justify-between mt-8">
                            <a href="{{ route('admin.users.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 dark:bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-white uppercase tracking-widest hover:bg-gray-400 dark:hover:bg-gray-500 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                {{ __('ཕྱིར་ལོག') }} (Back)
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                                {{ __('གསར་སྒྱུར།') }} (Update)
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Set all permission and category checkboxes for admin immediately
        (function() {
            const setAdminPermissionsAndCategories = function() {
                const roleSelect = document.getElementById('role');
                if (!roleSelect) return false; // Exit if role select not found

                const isAdmin = roleSelect.value === 'admin';
                if (!isAdmin) return false; // Exit if not admin

                // Set all permission checkboxes
                const permissionCheckboxes = document.querySelectorAll('input[type="checkbox"][name^="permissions"]');
                if (permissionCheckboxes.length > 0) {
                    permissionCheckboxes.forEach(function(checkbox) {
                        checkbox.checked = true;
                        checkbox.disabled = true;
                        // Force checked attribute
                        checkbox.setAttribute('checked', 'checked');
                    });
                }

                // Set all category checkboxes
                const categoryCheckboxes = document.querySelectorAll('input[type="checkbox"][name^="allowed_categories"]');
                if (categoryCheckboxes.length > 0) {
                    categoryCheckboxes.forEach(function(checkbox) {
                        checkbox.checked = true;
                        checkbox.disabled = true;
                        // Force checked attribute
                        checkbox.setAttribute('checked', 'checked');
                        // Visual styling to ensure the checked state is visible
                        checkbox.parentNode.classList.add('checkbox-checked');
                    });
                }

                return true; // Success
            };

            // Try immediately
            if (!setAdminPermissionsAndCategories()) {
                // If failed, try again when DOM is ready
                document.addEventListener('DOMContentLoaded', setAdminPermissionsAndCategories);
                // And one more time after a slight delay to ensure rendering is complete
                setTimeout(setAdminPermissionsAndCategories, 500);
                // Try once more after a longer delay
                setTimeout(setAdminPermissionsAndCategories, 1000);
            }
        })();

        // Check all categories when "ཡོངས་རྫོགས།" is in allowed_categories
        (function() {
            const checkAllCategoriesIfUniversal = function() {
                // Only run if we're in edit mode with existing allowed_categories
                @if(isset($user->allowed_categories) && is_array($user->allowed_categories))
                    const hasUniversalAccess = {{ in_array('ཡོངས་རྫོགས།', $user->allowed_categories) ? 'true' : 'false' }};
                    if (hasUniversalAccess) {
                        const categoryCheckboxes = document.querySelectorAll('input[type="checkbox"][name^="allowed_categories"]');
                        if (categoryCheckboxes.length > 0) {
                            categoryCheckboxes.forEach(function(checkbox) {
                                checkbox.checked = true;
                            });
                        }
                    }
                @endif
                return true;
            };

            // Try immediately and also after DOM is ready
            checkAllCategoriesIfUniversal();
            document.addEventListener('DOMContentLoaded', checkAllCategoriesIfUniversal);
            // And once more with a delay
            setTimeout(checkAllCategoriesIfUniversal, 500);
        })();

        // Main functionality
        document.addEventListener('DOMContentLoaded', function() {
            const roleSelect = document.getElementById('role');
            const tibNameField = document.getElementById('tibetan_name');
            const categoriesSection = document.getElementById('allowed-categories-section');
            const permissionCheckboxes = document.querySelectorAll('input[type="checkbox"][name^="permissions"]');
            const categoryCheckboxes = document.querySelectorAll('input[type="checkbox"][name^="allowed_categories"]');

            function updateNameField() {
                const selectedRole = roleSelect.value;
                if (selectedRole === 'reviewer') {
                    tibNameField.disabled = false;
                    tibNameField.required = true;
                } else {
                    tibNameField.disabled = true;
                    tibNameField.required = false;
                }
            }

            function updateCategoriesVisibility() {
                if (roleSelect.value === 'editor') {
                    categoriesSection.classList.remove('hidden');
                } else {
                    categoriesSection.classList.add('hidden');
                }
            }

            function updatePermissionCheckboxes() {
                const selectedRole = roleSelect.value;
                if (selectedRole === 'admin') {
                    // Check and disable all permission checkboxes for admin
                    permissionCheckboxes.forEach(function(checkbox) {
                        checkbox.checked = true;
                        checkbox.disabled = true;
                    });

                    // Also check and disable all category checkboxes for admin
                    categoryCheckboxes.forEach(function(checkbox) {
                        checkbox.checked = true;
                        checkbox.disabled = true;
                    });
                } else {
                    // Enable all checkboxes for non-admin roles
                    permissionCheckboxes.forEach(function(checkbox) {
                        checkbox.disabled = false;
                    });

                    categoryCheckboxes.forEach(function(checkbox) {
                        checkbox.disabled = false;
                    });

                    // For existing users, if they have "ཡོངས་རྫོགས།" in allowed_categories, check all categories
                    @if(isset($user->allowed_categories) && is_array($user->allowed_categories))
                        const hasUniversalAccess = {{ in_array('ཡོངས་རྫོགས།', $user->allowed_categories) ? 'true' : 'false' }};
                        if (hasUniversalAccess) {
                            categoryCheckboxes.forEach(function(checkbox) {
                                checkbox.checked = true;
                            });
                        }
                    @endif
                }
            }

            // Initial calls
            if (tibNameField) updateNameField();
            if (categoriesSection) updateCategoriesVisibility();
            updatePermissionCheckboxes();

            // On change
            roleSelect.addEventListener('change', function() {
                if (tibNameField) updateNameField();
                if (categoriesSection) updateCategoriesVisibility();
                updatePermissionCheckboxes();
            });
        });
    </script>
    @endpush
</x-app-layout>
