<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight text-center">
            {{ __('tasks.tasks_title') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">

                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <p class="text-base text-gray-500 dark:text-gray-400">
                                {{ $tasks->where('is_done', true)->count() }} из {{ $tasks->count() }} выполнено
                            </p>
                            <div class="mt-1 h-1 w-48 bg-gray-200 dark:bg-gray-700 rounded">
                                <div class="h-1 bg-green-500 rounded" style="width: {{ $tasks->count() ? round($tasks->where('is_done', true)->count() / $tasks->count() * 100) : 0 }}%"></div>
                            </div>
                        </div>
                        <div class="flex gap-2">
                            @php $filter = request('filter', 'all'); @endphp
                            <a href="{{ route('tasks') }}"
                               class="text-base px-3 py-1 border rounded-md {{ $filter === 'all' ? 'bg-gray-200 dark:bg-gray-600 border-gray-400 text-gray-900 dark:text-white' : 'border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                                Все
                            </a>
                            <a href="{{ route('tasks', ['filter' => 'active']) }}"
                               class="text-base px-3 py-1 border rounded-md {{ $filter === 'active' ? 'bg-gray-200 dark:bg-gray-600 border-gray-400 text-gray-900 dark:text-white' : 'border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                                Активные
                            </a>
                            <a href="{{ route('tasks', ['filter' => 'done']) }}"
                               class="text-base px-3 py-1 border rounded-md {{ $filter === 'done' ? 'bg-gray-200 dark:bg-gray-600 border-gray-400 text-gray-900 dark:text-white' : 'border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                                Выполнено
                            </a>
                        </div>
                    </div>

                    <ul class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($tasks as $task)
                            <li class="flex items-center gap-3 py-3 hover:bg-gray-50 dark:hover:bg-gray-700/50 rounded-lg px-2 -mx-2">
                                <form action="{{ route('tasks.toggle', $task) }}" method="POST" class="flex-shrink-0">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                            class="w-5 h-5 rounded-full border-2 flex items-center justify-center cursor-pointer
                                                   {{ $task->is_done ? 'bg-green-500 border-green-500' : 'border-gray-400 hover:border-green-400' }}">
                                        @if($task->is_done)
                                            <svg class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        @endif
                                    </button>
                                </form>

                                <span class="flex-1 text-lg {{ $task->is_done ? 'line-through text-gray-400' : 'text-gray-900 dark:text-gray-100' }}">
                                    {{ $task->title }}
                                </span>

                                <span class="text-base text-gray-400">{{ $task->due_date?->format('d M') }}</span>

                                @if($task->priority)
                                    <span class="text-sm px-2 py-0.5 rounded font-medium
                                        {{ $task->priority === 'high'   ? 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' : '' }}
                                        {{ $task->priority === 'medium' ? 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400' : '' }}
                                        {{ $task->priority === 'low'    ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : '' }}">
                                        {{ match($task->priority) {
                                            'high'   => 'Высокий',
                                            'medium' => 'Средний',
                                            'low'    => 'Низкий',
                                            default  => $task->priority
                                        } }}
                                    </span>
                                @endif
                            </li>
                        @empty
                            <li class="py-8 text-center text-lg text-gray-400">{{ __('tasks.no_tasks') }}</li>
                        @endforelse
                    </ul>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
