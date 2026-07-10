<div class="space-y-4">
    @if($task->description)
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700">
            <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">{{ __('tasks.description') }}</h4>
            <p class="text-gray-700 dark:text-gray-300 text-sm whitespace-pre-wrap">{{ $task->description }}</p>
        </div>
    @endif

    <div class="space-y-3">
        <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wide px-1">{{ __('tasks.task_chat') }}</h4>

        @forelse($task->taskComments as $comment)
            @include('task.partials.task-comment-item', ['comment' => $comment])
        @empty
            <div class="text-center py-8 text-gray-400 text-sm italic">
                {{ __('tasks.no_comments') }}
            </div>
        @endforelse
    </div>
</div>
