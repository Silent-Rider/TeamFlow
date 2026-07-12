<x-app-layout>
    <div class="py-0 sm:py-12" x-data="projectManager()">
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
                                <div class="w-[40px] flex-shrink-0 flex justify-end">
                                    @php $userId = auth()->id() @endphp
                                    @if($userId === $project->creator_id)
                                        <button type="button"
                                                @click.stop="openEditModal({{ $project->id }}, {
                                                        name: '{{ addslashes($project->name) }}',
                                                        description: '{{ addslashes($project->description ?? '') }}',
                                                        member_ids: {{ json_encode($project->users->pluck('id')->toArray()) }}
                                                })"
                                                class="p-2 text-gray-400 hover:text-blue-600 transition-all duration-200 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                            </li>
                        @empty
                            <li class="py-8 text-center text-lg text-gray-400">{{ __('projects.no_projects') }}</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
        <x-modal-window :type="'project'"/>
    </div>
</x-app-layout>
