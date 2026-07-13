<div x-show="detailsOpen"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 translate-x-10"
     x-transition:enter-end="opacity-100 translate-x-0"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100 translate-x-0"
     x-transition:leave-end="opacity-0 translate-x-10"
     class="hidden lg:flex flex-col w-[350px] shrink-0 border-l border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 h-full relative"
     style="display: none;">

    <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-start bg-white dark:bg-gray-800 shadow-sm z-10">
        <div class="pr-4 min-w-0 flex-1">
            <h3 class="font-bold text-lg leading-tight text-gray-900 dark:text-white break-words" x-text="currentTaskName || '{{ __('tasks.task_details') }}'"></h3>
            <span class="text-xs text-gray-500" x-show="currentTaskId">#<span x-text="currentTaskId"></span></span>
        </div>
        <button @click="detailsOpen = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 p-1 shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>

    <div class="flex-1 overflow-y-auto p-4 space-y-4 custom-scrollbar">
        <div x-html="detailsHtml" class="space-y-4"></div>
    </div>

    <div class="p-4 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
        <form @submit.prevent="addComment(currentTaskId)">
            <div class="relative flex items-center gap-2">
                <label class="flex-shrink-0 p-2 text-gray-400 hover:text-blue-600 cursor-pointer transition-colors flex items-center justify-center h-[40px] w-[40px] rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                    </svg>
                    <input type="file" class="hidden" @change="handleFileSelect($event)">
                </label>

                <textarea
                    x-model="newCommentText"
                    rows="1"
                    class="flex-1 border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm resize-none min-h-[40px] max-h-48 overflow-y-auto py-2 px-3"
                    placeholder="{{ __('tasks.write_comment_placeholder') }}"
                    @input="$el.style.height = 'auto'; $el.style.height = ($el.scrollHeight + 2) + 'px';"
                    x-init="$watch('newCommentText', () => {
                    $el.style.height = 'auto';
                    if ($el.value) $el.style.height = ($el.scrollHeight) + 'px';
                    })"
                ></textarea>

                <button type="submit"
                        :disabled="!newCommentText.trim()"
                        class="flex-shrink-0 p-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors flex items-center justify-center h-[40px] w-[40px]">
                    <svg class="w-5 h-5 transform rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                </button>
            </div>

            <div x-show="selectedFile" class="mt-1 text-xs text-gray-500 truncate" x-text="'Файл: ' + selectedFile"></div>
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
        <form @submit.prevent="addComment(currentTaskId)" class="flex gap-2 items-center">
            <label class="flex-shrink-0 p-3 text-gray-400 hover:text-blue-600 cursor-pointer transition-colors rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 active:scale-95">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                </svg>
                <input type="file" class="hidden" @change="handleFileSelect($event)">
            </label>

            <textarea
                x-model="newCommentText"
                rows="1"
                class="flex-1 border border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none max-h-40 overflow-y-auto transition-all"
                placeholder="{{ __('tasks.comment_placeholder') }}"
                @input="$el.style.height = 'auto'; $el.style.height = ($el.scrollHeight + 2) + 'px';"
                x-init="$watch('newCommentText', () => {
                $el.style.height = 'auto';
                if ($el.value) $el.style.height = ($el.scrollHeight) + 'px';
                })"
            ></textarea>

            <button type="submit"
                    :disabled="!newCommentText.trim()"
                    class="p-3 bg-blue-600 text-white rounded-xl disabled:opacity-50 disabled:cursor-not-allowed active:scale-95 transition-transform shadow-md shrink-0">
                <svg class="w-5 h-5 transform rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
            </button>
        </form>

        <div x-show="selectedFileName" class="mt-1 ml-2 text-xs text-gray-500 truncate" x-text="'Файл: ' + selectedFileName"></div>
    </div>
</div>
