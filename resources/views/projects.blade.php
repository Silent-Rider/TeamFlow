<x-app-layout>
    <div class="py-0 sm:py-12 h-[calc(100vh-4.05rem)]">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 h-full">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg h-full flex transition-all duration-300 ease-in-out">

                <div x-data="projectManager()"
                     class="min-w-0 transition-all duration-300 flex flex-col h-full relative border-r border-gray-200 dark:border-gray-700"
                     :class="$store.projectPanel.open ? 'hidden lg:flex lg:w-[300px]' : 'flex w-full'">

                    <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center shrink-0">
                        <h2
                            :class="$store.projectPanel.open ? 'hidden' : ''"
                            class="font-semibold text-gray-800 dark:text-gray-200 text-xl"
                        >
                            {{ __('projects.title') }}
                        </h2>
                        <button @click="openCreateModal()" class="btn-primary text-sm py-2 px-4">
                            {{ __('projects.add_button') }}
                        </button>
                    </div>

                    <div class="flex-1 overflow-y-auto custom-scrollbar p-6">
                        <ul class="divide-y divide-gray-100 dark:divide-gray-700">
                            @forelse($projects as $project)
                                <li class="flex items-center gap-3 py-3 hover:bg-gray-50 dark:hover:bg-gray-700/50 rounded-lg px-2 -mx-2 cursor-pointer"
                                    @click="$store.projectPanel.select({{ $project->id }}, '{{ addslashes($project->name) }}')"
                                    :class="{ 'bg-blue-50 dark:bg-blue-900/20': $store.projectPanel.projectId === {{ $project->id }} }">
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

                    <x-modal-window :type="'project'"/>
                </div>

                <div x-data="taskManager()"
                     x-show="$store.projectPanel.open"
                     class="flex-1 min-w-0 flex h-full"
                     style="display: none;">

                    <div class="min-w-0 flex flex-col h-full relative border-r border-gray-200 dark:border-gray-700"
                         :class="detailsOpen ? 'hidden lg:flex lg:w-1/2' : 'flex w-full'">

                        <div class="flex items-center gap-3 p-4 border-b border-gray-200 dark:border-gray-700 shrink-0">
                            <button @click="$store.projectPanel.close()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 p-1 lg:hidden">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                            </button>
                            <h3 class="font-bold text-lg text-gray-900 dark:text-white truncate flex-1" x-text="$store.projectPanel.projectName"></h3>
                            <button @click="$store.projectPanel.close()" class="hidden lg:block text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 p-1">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>

                        <div class="flex-1 flex flex-col min-h-0" x-ref="taskListContainer" x-html="$store.projectPanel.html"></div>
                    </div>

                    @include('task.partials.task-details-panel')

                    <x-modal-window :type="'task'"/>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
