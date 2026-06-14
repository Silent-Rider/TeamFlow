<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600&display=swap" rel="stylesheet">
<x-app-layout>
    <div class="py-0 sm:py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <ul class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($users as $user)
                            <li class="flex items-center gap-3 py-3 hover:bg-gray-50 dark:hover:bg-gray-700/50 rounded-lg px-2 -mx-2">
                                <div class="flex items-center gap-3">
                                    <div id="avatar-container" class="relative h-12 w-12 shrink-0 group"
                                         data-user-name="{{ $user->name }}">
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
                                                class="h-full w-full text-2xl border-2 border-white/20 shadow-sm"
                                            />
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                    <span class="block text-lg text-gray-900 dark:text-gray-100" style="font-family: 'Nunito', sans-serif;">
                                        {{ $user->name }}
                                    </span>
                                    </div>
                                </div>
                            </li>
                        @empty
                            <li class="py-8 text-center text-lg text-gray-400">{{ __('users.no_users') }}</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
