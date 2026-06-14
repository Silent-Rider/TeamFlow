<x-app-layout>
    <div class="py-0 sm:py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <ul class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($projects as $project)
                            <li class="flex items-center gap-3 py-3 hover:bg-gray-50 dark:hover:bg-gray-700/50 rounded-lg px-2 -mx-2">
                                <div class="flex-1 min-w-0">
                                    <span class="block text-lg text-gray-900 dark:text-gray-100">
                                        {{ $project->name }}
                                    </span>
                                </div>
                            </li>
                        @empty
                            <li class="py-8 text-center text-lg text-gray-400">{{ __('projects.no_projects') }}</li>
                        @endforelse
                    </ul>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
