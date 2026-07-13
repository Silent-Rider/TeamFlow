<div class="space-y-4">
    <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700 flex items-center gap-3">
        <div class="flex-shrink-0">
            @if($task->assignee?->avatar && Storage::disk('public')->exists($task->assignee->avatar))
                <img src="{{ Storage::url($task->assignee->avatar) }}" class="w-10 h-10 rounded-full object-cover border-2 border-gray-200 dark:border-gray-700 shadow-sm">
            @else
                <x-avatar-placeholder :name="$task->assignee->name"
                                      class="w-10 h-10 text-sm" />
            @endif
        </div>
        <div class="min-w-0">
            <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-0.5">{{ __('tasks.assignee_label') }}</h4>
            <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                {{ $task->assignee?->name ?? __('tasks.no_assignee') }}
            </p>
        </div>
    </div>

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
