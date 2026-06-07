<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="shrink-0 flex items-center">
                <a href="{{ route('tasks') }}" class="nav-logo sm:nav-logo--large">
                    <img src="{{ asset('images/teamflow_logo.svg') }}" alt="logo" width="45" height="45">
                    {{ config('app.name', 'MyApp') }}<span>.</span>
                </a>
            </div>

            <div class="hidden sm:flex items-center gap-14">
                <x-nav-link :href="route('tasks')" :active="request()->routeIs('tasks')">
                    {{ __('navigation.tasks_link') }}
                </x-nav-link>
                <x-nav-link :href="route('projects')" :active="request()->routeIs('projects')">
                    {{ __('navigation.projects_link') }}
                </x-nav-link>
                <x-nav-link :href="route('users')" :active="request()->routeIs('users')">
                    {{ __('navigation.users_link') }}
                </x-nav-link>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('navigation.profile_link') }}
                        </x-dropdown-link>

                        <div class="px-4 py-2 flex items-center justify-between">
                            <span class="text-sm text-gray-700 dark:text-gray-300">Тёмная тема</span>
                            <button
                                x-data="{ on: document.documentElement.classList.contains('dark') }"
                                x-on:click.stop="
                                    on = !on;
                                    document.documentElement.classList.toggle('dark', on);
                                    localStorage.theme = on ? 'dark' : 'light'
                                "
                                :class="on ? 'bg-blue-500' : 'bg-gray-300'"
                                class="relative w-11 h-6 rounded-full transition-colors duration-200 focus:outline-none">
                                <span
                                    :class="on ? 'translate-x-6' : 'translate-x-1'"
                                    class="absolute top-1 left-0 w-4 h-4 bg-white rounded-full shadow transition-transform duration-200">
                                </span>
                            </button>
                        </div>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                             onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('navigation.log_out_button') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden absolute left-0 right-0 z-50 bg-white dark:bg-gray-700 shadow-lg ring-1 ring-black ring-opacity-5">
        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('navigation.profile_link') }}
                </x-responsive-nav-link>

                <div class="px-4 py-2 flex items-center justify-between">
                    <span class="text-base font-medium text-gray-600 dark:text-gray-400">Тёмная тема</span>
                    <button
                        x-data="{ on: document.documentElement.classList.contains('dark') }"
                        x-on:click.stop="
                                    on = !on;
                                    document.documentElement.classList.toggle('dark', on);
                                    localStorage.theme = on ? 'dark' : 'light'
                                "
                        :class="on ? 'bg-blue-500' : 'bg-gray-300'"
                        class="relative w-11 h-6 rounded-full transition-colors duration-200 focus:outline-none">
                        <span
                            :class="on ? 'translate-x-6' : 'translate-x-1'"
                            class="absolute top-1 left-0 w-4 h-4 bg-white rounded-full shadow transition-transform duration-200">
                        </span>
                    </button>
                </div>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                                           onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('navigation.log_out_button') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>

    <div id="bottom-nav" class="fixed bottom-0 left-0 right-0 z-50 flex sm:hidden bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">

        <a href="{{ route('tasks') }}"
           class="flex flex-col items-center justify-center flex-1 py-3 gap-1 text-xs
              {{ request()->routeIs('tasks') ? 'text-blue-500' : 'text-gray-500 dark:text-gray-400' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            {{ __('navigation.tasks_link') }}
        </a>

        <a href="{{ route('projects') }}"
           class="flex flex-col items-center justify-center flex-1 py-3 gap-1 text-xs
              {{ request()->routeIs('projects') ? 'text-blue-500' : 'text-gray-500 dark:text-gray-400' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18"/>
            </svg>
            {{ __('navigation.projects_link') }}
        </a>

        <a href="{{ route('users') }}"
           class="flex flex-col items-center justify-center flex-1 py-3 gap-1 text-xs
              {{ request()->routeIs('users') ? 'text-blue-500' : 'text-gray-500 dark:text-gray-400' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            {{ __('navigation.users_link') }}
        </a>
    </div>
</nav>
