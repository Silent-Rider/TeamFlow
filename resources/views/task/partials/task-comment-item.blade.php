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

        @if($comment->attachments->isNotEmpty())
            <div class="flex flex-wrap gap-2 mt-2">
                @foreach($comment->attachments as $attachment)
                    @php
                        $isImage = in_array($attachment->extension, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                        $url = route('tasks.attachments.download', $attachment);
                    @endphp

                    @if($isImage)
                        <a href="{{ $url }}" target="_blank" class="block overflow-hidden rounded-lg border border-gray-200 dark:border-gray-700 hover:opacity-90 transition-opacity">
                            <img src="{{ Storage::url($attachment->filepath) }}"
                                 alt="{{ $attachment->name }}"
                                 class="h-24 w-auto object-cover">
                        </a>
                    @else
                        <a href="{{ $url }}"
                           class="inline-flex items-center gap-2 px-3 py-2 bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors group max-w-[200px]">

                            <div class="w-8 h-8 rounded bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center shrink-0">
                                @if($attachment->extension === 'pdf')
                                    <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/><path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/></svg>
                                @elseif(in_array($attachment->extension, ['docx', 'doc']))
                                    <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/></svg>
                                @else
                                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                @endif
                            </div>

                            <div class="flex-1 min-w-0 text-left">
                                <p class="text-xs font-medium text-gray-900 dark:text-gray-200 truncate">{{ $attachment->name }}</p>
                                <p class="text-[10px] text-gray-500 uppercase">{{ $attachment->extension }}</p>
                            </div>
                        </a>
                    @endif
                @endforeach
            </div>
        @endif
    </div>
</div>
