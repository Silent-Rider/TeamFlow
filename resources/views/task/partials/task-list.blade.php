@php
    $filter = $filter ?? 'all';
    $projectId = $projectId ?? null;
    $embedded = $embedded ?? false;
@endphp

<div class="sticky top-0 z-20 bg-white/95 dark:bg-gray-800/95 backdrop-blur-sm border-b border-gray-100 dark:border-gray-700 p-6 pb-4">
    @if($embedded)
        <div class="flex justify-end mb-4">
            <button type="button"
                    @click="openCreateModal({{ $projectId ?? 'null' }})"
                    class="btn-primary text-sm py-2 px-4">
                {{ __('tasks.add_button') }}
            </button>
        </div>
    @endif

    <div class="flex flex-col sm:flex-row items-center sm:justify-between gap-3">

        @if($filter === 'all')
            <div class="order-2 sm:order-1 w-full sm:w-auto">
                <p class="text-sm sm:text-base text-center sm:text-left text-gray-500 dark:text-gray-400">
                    <span x-text="doneTasks"></span> {{ __('tasks.of') }} <span x-text="totalTasks"></span> {{ __('tasks.completed_lower') }}
                </p>
                <div class="mt-1 h-1 w-48 bg-gray-200 dark:bg-gray-700 rounded mx-auto sm:mx-0">
                    <div class="h-1 bg-green-500 rounded transition-all duration-300"
                         :style="`width: ${totalTasks ? Math.round((doneTasks / totalTasks) * 100) : 0}%`">
                    </div>
                </div>
            </div>
        @else
            <div class="hidden sm:block sm:order-1"></div>
        @endif

        <div class="order-1 sm:order-2 flex flex-wrap sm:flex-nowrap gap-2 justify-center sm:justify-end w-full sm:w-auto">
            @foreach(['all' => __('tasks.all'), 'active' => __('tasks.active'), 'done' => __('tasks.completed_upper')] as $key => $label)
                @php
                    $btnClass = 'text-sm sm:text-base px-3 py-1 border rounded-md text-center '
                        . ($filter === $key
                            ? 'bg-gray-200 dark:bg-gray-600 border-gray-400 text-gray-900 dark:text-white'
                            : 'border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700');
                @endphp
                @if($embedded)
                    <button type="button" @click="$store.projectPanel.changeFilter('{{ $key }}')" class="{{ $btnClass }}">{{ $label }}</button>
                @else
                    <a href="{{ route('tasks', $key === 'all' ? [] : ['filter' => $key]) }}" class="{{ $btnClass }}">{{ $label }}</a>
                @endif
            @endforeach
        </div>
    </div>
</div>

<div class="flex-1 overflow-y-auto p-6 custom-scrollbar" style="scrollbar-gutter: stable;">
    <ul class="divide-y divide-gray-100 dark:divide-gray-700 pb-6">
        @forelse($tasks as $task)
            @php $priority = $task->priority->value; @endphp
            <li class="flex items-center gap-3 py-3 hover:bg-gray-50 dark:hover:bg-gray-700/50 rounded-lg px-2 -mx-2 group cursor-pointer"
                @click="openTaskDetails({{ $task->id }})"
                :class="{ 'bg-blue-50 dark:bg-blue-900/20': detailsOpen && currentTaskId === {{ $task->id }} }">

                <button type="button" @click.prevent="toggleTask({{ $task->id }}, $event, '{{ $filter }}'); $event.stopPropagation();"
                        data-task-id="{{ $task->id }}" data-is-done="{{ $task->is_done ? '1' : '0' }}"
                        :class="{ 'bg-green-500 border-green-500': isTaskDone({{ $task->id }}), 'border-gray-400 hover:border-green-400': !isTaskDone({{ $task->id }}) }"
                        class="w-5 h-5 rounded-full border-2 flex items-center justify-center cursor-pointer transition-colors flex-shrink-0 z-10">
                    <svg x-show="isTaskDone({{ $task->id }})" class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                    </svg>
                </button>

                <div class="flex-1 min-w-0">
                    <span class="block text-base sm:text-lg truncate transition-colors"
                          :class="{ 'line-through text-gray-400 dark:text-gray-500': isTaskDone({{ $task->id }}), 'text-gray-900 dark:text-gray-100': !isTaskDone({{ $task->id }}) }">
                        {{ $task->name }}
                    </span>

                    <div class="flex items-center gap-2 mt-0.5 sm:hidden">
                        <span class="text-sm text-gray-400 whitespace-nowrap">{{ $task->due_date?->format('d M') }}</span>
                        @if($priority)
                            <span class="text-xs px-2 py-0.5 rounded font-medium truncate max-w-[100px]
                                {{ $priority === 'high' ? 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' : '' }}
                                {{ $priority === 'medium' ? 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400' : '' }}
                                {{ $priority === 'low' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : '' }}">
                                {{ match($priority) { 'high' => __('tasks.priority.high'), 'medium' => __('tasks.priority.medium'), 'low' => __('tasks.priority.low'), default => $priority } }}
                            </span>
                        @endif
                    </div>
                </div>

                <div class="hidden sm:flex items-center gap-3 flex-shrink-0">
                    <span class="text-base text-gray-400 whitespace-nowrap">{{ $task->due_date?->format('d M') }}</span>
                    @if($priority)
                        <span class="text-sm px-2 py-0.5 rounded font-medium whitespace-nowrap
                            {{ $priority === 'high' ? 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' : '' }}
                            {{ $priority === 'medium' ? 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400' : '' }}
                            {{ $priority === 'low' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : '' }}">
                            {{ match($priority) { 'high' => __('tasks.priority.high'), 'medium' => __('tasks.priority.medium'), 'low' => __('tasks.priority.low'), default => $priority } }}
                        </span>
                    @endif
                </div>

                <div class="w-[40px] flex-shrink-0 flex justify-end">
                    @if(auth()->id() === $task->creator_id)
                        <button type="button"
                                @click.stop="openEditModal({{ $task->id }}, {
                                    name: '{{ addslashes($task->name) }}',
                                    description: '{{ addslashes($task->description ?? '') }}',
                                    assignee_id: {{ $task->assignee_id }},
                                    priority: '{{ $task->priority->value }}',
                                    due_date: '{{ $task->due_date?->format('Y-m-d') }}',
                                    project_id: {{ $task->project_id ?? 'null' }}
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
            <li class="py-8 text-center text-lg text-gray-400">{{ __('tasks.no_tasks') }}</li>
        @endforelse
    </ul>

    @if(!$embedded && method_exists($tasks, 'links'))
        <div class="mt-2">{{ $tasks->links() }}</div>
    @endif
</div>
