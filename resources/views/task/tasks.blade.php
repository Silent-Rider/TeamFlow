<x-app-layout>
    <div class="py-0 sm:py-12 h-[calc(100vh-4.05rem)]" x-data="taskManager()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 h-full">

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg h-full flex transition-all duration-300 ease-in-out">

                <div id="task-list-wrapper"
                     class="flex-1 min-w-0 transition-all duration-300 flex flex-col h-full relative"
                     :class="detailsOpen ? 'lg:w-3/4 w-full' : 'w-full'">

                    <div id="filter-header"
                         class="sticky top-0 z-20 bg-white/95 dark:bg-gray-800/95 backdrop-blur-sm border-b border-gray-100 dark:border-gray-700 p-6 pb-4 -mx-0">

                        <div class="flex flex-col sm:flex-row items-center sm:justify-between gap-3">
                            @php $filter = request('filter', 'all'); @endphp

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

                            <div class="order-1 sm:order-2 flex flex-wrap sm:flex-nowrap gap-2 justify-end w-full sm:w-auto">
                                <a href="{{ route('tasks') }}" class="text-sm sm:text-base px-3 py-1 border rounded-md text-center {{ $filter === 'all' ? 'bg-gray-200 dark:bg-gray-600 border-gray-400 text-gray-900 dark:text-white' : 'border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' }}">{{ __('tasks.all') }}</a>
                                <a href="{{ route('tasks', ['filter' => 'active']) }}" class="text-sm sm:text-base px-3 py-1 border rounded-md text-center {{ $filter === 'active' ? 'bg-gray-200 dark:bg-gray-600 border-gray-400 text-gray-900 dark:text-white' : 'border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' }}">{{ __('tasks.active') }}</a>
                                <a href="{{ route('tasks', ['filter' => 'done']) }}" class="text-sm sm:text-base px-3 py-1 border rounded-md text-center {{ $filter === 'done' ? 'bg-gray-200 dark:bg-gray-600 border-gray-400 text-gray-900 dark:text-white' : 'border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' }}">{{ __('tasks.completed_upper') }}</a>
                            </div>
                        </div>
                    </div>

                    <div id="task-scroll-area"
                         class="flex-1 overflow-y-auto p-6 custom-scrollbar"
                         style="scrollbar-gutter: stable;">

                        <ul class="divide-y divide-gray-100 dark:divide-gray-700 pb-6">
                            @forelse($tasks as $task)
                                @php $priority = $task->priority->value; @endphp
                                <li class="flex items-center gap-3 py-3 hover:bg-gray-50 dark:hover:bg-gray-700/50 rounded-lg px-2 -mx-2 group cursor-pointer"
                                    @click="openTaskDetails({{ $task->id }})"
                                    :class="{ 'bg-blue-50 dark:bg-blue-900/20': detailsOpen && currentTaskId === {{ $task->id }} }">

                                    <button type="button" @click.prevent="toggleTask({{ $task->id }}, $event); $event.stopPropagation();"
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
                                </li>
                            @empty
                                <li class="py-8 text-center text-lg text-gray-400">{{ __('tasks.no_tasks') }}</li>
                            @endforelse
                        </ul>
                    </div>
                </div>

                <div x-show="detailsOpen"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-x-10"
                     x-transition:enter-end="opacity-100 translate-x-0"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-x-0"
                     x-transition:leave-end="opacity-0 translate-x-10"
                     class="hidden lg:flex flex-col w-1/4 min-w-[320px] max-w-[400px] border-l border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 h-full relative"
                     style="display: none;">

                    <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-start bg-white dark:bg-gray-800 shadow-sm z-10">
                        <div class="pr-4">
                            <h3 class="font-bold text-lg leading-tight text-gray-900 dark:text-white" x-text="currentTaskName || '{{ __('tasks.task_details') }}'"></h3>
                            <span class="text-xs text-gray-500" x-show="currentTaskId">#<span x-text="currentTaskId"></span></span>
                        </div>
                        <button @click="detailsOpen = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 p-1">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    <div class="flex-1 overflow-y-auto p-4 space-y-4 custom-scrollbar">
                        <div x-html="detailsHtml" class="space-y-4"></div>
                    </div>

                    <div class="p-4 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
                        <form @submit.prevent="addComment(currentTaskId)">
                            <div class="relative">
                                <textarea
                                    x-model="newCommentText"
                                    rows="2"
                                    class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm resize-none pr-10"
                                    placeholder="{{ __('tasks.write_comment_placeholder') }}"></textarea>
                                <button type="submit"
                                        :disabled="!newCommentText.trim()"
                                        class="absolute right-2 bottom-2 p-1.5 bg-blue-600 text-white rounded-md hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div x-show="detailsOpen"
                     class="fixed inset-0 z-[60] bg-white dark:bg-gray-900 lg:hidden flex flex-col h-[100dvh] w-full"
                     style="display: none;"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="translate-y-full opacity-0"
                     x-transition:enter-end="translate-y-0 opacity-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="translate-y-0 opacity-100"
                     x-transition:leave-end="translate-y-full opacity-0">

                    <div class="flex-none p-4 border-b border-gray-200 dark:border-gray-700 bg-white/95 dark:bg-gray-900/95 backdrop-blur-sm z-10 flex items-center justify-between shrink-0">
                        <div class="flex items-center gap-3 overflow-hidden">
                            <button @click="closeTaskDetails()" class="p-2 -ml-2 text-gray-600 dark:text-gray-300 active:bg-gray-100 dark:active:bg-gray-800 rounded-full transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                            </button>
                            <h3 class="font-bold text-lg truncate text-gray-900 dark:text-white" x-text="currentTaskName"></h3>
                        </div>
                    </div>

                    <div class="flex-1 overflow-y-auto p-4 space-y-4 custom-scrollbar bg-gray-50 dark:bg-gray-900 min-h-0">
                        <div x-html="detailsHtml" class="space-y-4 pb-2"></div>
                    </div>

                    <div class="flex-none p-3 pb-[max(1rem,env(safe-area-inset-bottom))] border-t border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)] shrink-0">
                        <form @submit.prevent="addComment(currentTaskId)" class="flex gap-2 items-end">
                            <textarea
                                x-model="newCommentText"
                                rows="1"
                                class="flex-1 border border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none max-h-32 transition-all"
                                placeholder="{{ __('tasks.comment_placeholder') }}"
                                @input="$el.style.height = ''; $el.style.height = $el.scrollHeight + 'px'"></textarea>
                            <button type="submit"
                                    :disabled="!newCommentText.trim()"
                                    class="p-3 bg-blue-600 text-white rounded-xl disabled:opacity-50 disabled:cursor-not-allowed active:scale-95 transition-transform shadow-md shrink-0">
                                <svg class="w-5 h-5 transform rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
