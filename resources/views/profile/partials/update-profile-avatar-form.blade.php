<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('profile.profile_avatar') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('profile.profile_avatar_desc') }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.avatar.update') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <div>
            <div id="avatar-container" class="relative h-48 w-48 shrink-0 group" data-user-name="{{ $user->name }}">
                @if($user->avatar && Storage::disk('public')->exists($user->avatar))
                    <img
                        id="avatar-preview"
                        src="{{ Storage::url($user->avatar) }}"
                        alt="{{ $user->name }}"
                        class="h-full w-full rounded-full object-cover border-2 border-gray-200 dark:border-gray-700 shadow-sm"
                    />
                @else
                    <x-avatar-placeholder
                        :name="$user->name"
                        class="h-full w-full text-8xl border-2 border-white/20 shadow-sm"
                    />
                @endif

                <label
                    for="avatar-input"
                    class="absolute inset-0 flex items-center justify-center bg-black/50 rounded-full opacity-0 group-hover:opacity-100 cursor-pointer transition-opacity duration-200">
                    <svg class="w-8 h-8 text-white drop-shadow-md" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </label>
            </div>

            <input id="avatar-input" name="avatar" type="file" class="hidden" accept=".jpg,.jpeg,.png,.webp" onchange="updateAvatarPreview(this)" />

            <x-input-error class="mt-2" :messages="$errors->get('avatar')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button class="w-48 justify-center">
                {{ __('profile.save_button') }}
            </x-primary-button>

            @if (session('status') === 'profile-avatar-updated')
            <p
                x-data="{ show: true }"
                x-show="show"
                x-transition
                x-init="setTimeout(() => show = false, 2000)"
                class="text-sm text-gray-600 dark:text-gray-400"
            >{{ __('profile.saved_status') }}</p>
            @endif
        </div>
    </form>
</section>
