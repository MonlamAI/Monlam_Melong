<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />
        </div>

        <!-- User Role Display (Read-only) -->
        <div>
            <x-input-label for="role" :value="__('Role')" />
            <div class="mt-1 block w-full p-2.5 bg-gray-100 border border-gray-300 text-gray-900 rounded-lg">
                @php
                    $roleLabels = [
                        'admin' => 'Admin',
                        'chief_editor' => 'Chief Editor',
                        'editor' => 'Editor',
                        'benchmark_editor' => 'Benchmark Editor',
                        'reviewer' => 'Reviewer'
                    ];

                    $roleLabel = isset($roleLabels[$user->role]) ? $roleLabels[$user->role] : ucfirst($user->role);
                @endphp
                {{ $roleLabel }}
            </div>
            <p class="mt-1 text-sm text-gray-500">{{ __('Your role can only be changed by an administrator.') }}</p>
        </div>

        <!-- Allowed Categories Display (Read-only) -->
        @if($user->allowed_categories || $user->isAdmin() || $user->isChiefEditor())
        <div>
            <x-input-label for="allowed_categories" :value="__('Allowed Categories')" />
            <div class="mt-1 block w-full p-2.5 bg-gray-100 border border-gray-300 text-gray-900 rounded-lg">
                @if($user->isAdmin() || $user->isChiefEditor() || (isset($user->allowed_categories) && is_array($user->allowed_categories) && in_array('ཡོངས་རྫོགས།', $user->allowed_categories)))
                    {{ __('All Categories (Universal Access)') }}
                @elseif(isset($user->allowed_categories) && is_array($user->allowed_categories) && count($user->allowed_categories) > 0)
                    <ul class="list-disc pl-5">
                        @foreach($user->allowed_categories as $category)
                            <li class="tibetan-font">{{ $category }}</li>
                        @endforeach
                    </ul>
                @else
                    {{ __('No specific category restrictions') }}
                @endif
            </div>
            <p class="mt-1 text-sm text-gray-500">{{ __('Your category permissions can only be changed by an administrator.') }}</p>
        </div>
        @endif

        <!-- Permissions Display (Read-only) -->
        @if($user->permissions)
        <div>
            <x-input-label for="permissions" :value="__('Special Permissions')" />
            <div class="mt-1 block w-full p-2.5 bg-gray-100 border border-gray-300 text-gray-900 rounded-lg">
                @if($user->isAdmin())
                    {{ __('All permissions (Administrator)') }}
                @elseif(isset($user->permissions) && is_array($user->permissions) && count($user->permissions) > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                        @foreach($user->permissions as $feature => $actions)
                            <div>
                                <strong>{{ $feature == 'sections' ? 'Tags' : ucfirst(str_replace('_', ' ', $feature)) }}:</strong>
                                <ul class="list-disc pl-5">
                                    @foreach($actions as $action => $enabled)
                                        @if($enabled)
                                            <li>{{ ucfirst($action) }}</li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                        @endforeach
                    </div>
                @else
                    {{ __('No special permissions') }}
                @endif
            </div>
            <p class="mt-1 text-sm text-gray-500">{{ __('Your permissions can only be changed by an administrator.') }}</p>
        </div>
        @endif

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
