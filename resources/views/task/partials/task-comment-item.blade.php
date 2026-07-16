<div class="bg-white dark:bg-gray-800 p-3 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700 flex gap-3">
    <div class="flex-shrink-0">
        @if($comment->user->avatar && Storage::disk('public')->exists($comment->user->avatar))
            <img src="{{ Storage::url($comment->user->avatar) }}" class="w-8 h-8 rounded-full object-cover">
        @else
            <x-avatar-placeholder :name="$comment->user->name" class="w-8 h-8 text-xs" />
        @endif
    </div>

    <div class="flex-1 min-w-0">
        <div class="flex justify-between items-baseline mb-1">
            <span class="font-semibold text-sm text-gray-900 dark:text-white">{{ $comment->user->name }}</span>
            <span class="text-xs text-gray-400">
                {{ $comment->created_at->translatedFormat('d F, H:i') }}
            </span>
        </div>
        <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-wrap break-words">{{ $comment->content }}</p>
    </div>
</div>
