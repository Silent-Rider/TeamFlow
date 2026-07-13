@php use App\Enums\ModalWindowType; @endphp
<div x-show="modalOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="closeModal()"></div>

    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
        <div x-show="modalOpen"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             class="relative transform overflow-hidden rounded-lg bg-white dark:bg-gray-800 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-gray-200 dark:border-gray-700">

            <form :action="formAction" method="POST" enctype="multipart/form-data" class="p-6">
                @csrf
                <input type="hidden" name="_method" :value="isEdit ? 'PUT' : 'POST'">

                @if ($type === ModalWindowType::TASK)
                    <template x-if="!isEdit && formData.project_id">
                        <input type="hidden" name="project_id" :value="formData.project_id">
                    </template>
                @endif

                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white"
                        x-text="isEdit ? '{{ $labels['edit_title'] }}' : '{{ $labels['create_title'] }}'"></h3>
                    <button type="button" @click="closeModal()" class="text-gray-400 hover:text-gray-500">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                @if (isset($labels['logo_placeholder']))
                    <div class="mb-6 flex justify-center">
                        <div class="relative h-24 w-24 rounded-full bg-gray-100 dark:bg-gray-700 border-2 border-dashed border-gray-300 dark:border-gray-600
                                flex items-center justify-center overflow-hidden cursor-pointer hover:border-blue-500 dark:hover:border-blue-400 transition-colors">
                            <img x-show="previewLogo" :src="previewLogo" class="h-full w-full object-cover">
                            <div x-show="!previewLogo" class="text-gray-400 flex flex-col items-center">
                                <svg class="w-8 h-8 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828
                                            0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span class="text-xs">{{ $labels['logo_placeholder'] }}</span>
                            </div>
                            <input type="file" name="logo" accept=".jpg,.jpeg,.png,.webp"
                                   class="absolute inset-0 opacity-0 cursor-pointer" @change="handleLogoUpload">
                        </div>
                    </div>
                @endif

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            {{ $labels['name_label'] }}
                        </label>
                        <input
                            type="text"
                            name="name"
                            x-model="formData.name"
                            maxlength="{{ $type === ModalWindowType::TASK ? 64 : 32 }}"
                            required
                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            {{ $labels['description_label'] }}
                        </label>
                        <textarea
                            name="description"
                            x-model="formData.description"
                            rows="1"
                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm resize-none min-h-[80px] max-h-40 overflow-y-auto"
                            @input="$el.style.height = 'auto'; $el.style.height = ($el.scrollHeight + 2) + 'px'"
                            x-init="$watch('formData.description', value => {
                                        $nextTick(() => {
                                            if (value) {
                                                $el.style.height = 'auto';
                                                $el.style.height = ($el.scrollHeight + 2) + 'px';
                                            } else {
                                                $el.style.height = 'auto';
                                            }
                                        });
                                    })"
                        ></textarea>
                    </div>

                    @if (isset($labels['members_label']))
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                {{ $labels['members_label'] }}
                            </label>

                            <div class="flex flex-wrap gap-2 mb-2 min-h-[40px] p-2 border border-gray-200 dark:border-gray-600 rounded-md bg-white dark:bg-gray-900">
                                <template x-for="userId in formData.members" :key="userId">
                                    <template x-if="userId != {{ auth()->id() }}">
                                        <div class="inline-flex items-center px-2 py-1 rounded text-sm bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                            <span x-text="availableUsers.find(u => u.id == userId)?.name || '...'"></span>
                                            <button type="button"
                                                    @click.stop="formData.members = formData.members.filter(id => id !== Number(userId))"
                                                    class="ml-1.5 text-blue-600 dark:text-blue-300 hover:text-blue-800 dark:hover:text-blue-100 focus:outline-none">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </template>
                                </template>

                                <template x-if="formData.members.length === 0">
                                    <span class="text-sm text-gray-400 italic self-center">{{ __('projects.members_not_selected') }}</span>
                                </template>
                            </div>

                            <div class="relative">
                                <select
                                    @change="$event.target.value && formData.members.push(Number($event.target.value)); $event.target.value = ''"
                                    class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                >
                                    <option value="" disabled selected>{{ __('projects.add_member') }}</option>
                                    <template x-for="user in availableUsers" :key="user.id">
                                        <template x-if="!formData.members.includes(user.id)">
                                            <option :value="user.id" x-text="user.name"></option>
                                        </template>
                                    </template>
                                </select>
                            </div>

                            <template x-for="memberId in formData.members" :key="'hidden-'+memberId">
                                <input type="hidden" name="members[]" :value="memberId">
                            </template>
                        </div>
                    @endif

                    @if (isset($labels['assignee_label']))
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                {{ $labels['assignee_label'] }}
                            </label>

                            <select
                                name="assignee_id"
                                x-model="formData.assignee_id"
                                required
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            >
                                <template x-if="availableUsers.length === 0">
                                    <option value="" disabled selected>{{ __('tasks.loading_users') }}</option>
                                </template>

                                <template x-for="user in availableUsers" :key="user.id">
                                    <option
                                        :value="user.id"
                                        :selected="Number(user.id) === Number(formData.assignee_id)"
                                        x-text="user.name"
                                    ></option>
                                </template>
                            </select>
                        </div>
                    @endif

                    @if (isset($labels['priority_label']))
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                {{ $labels['priority_label'] }}
                            </label>
                            <select
                                name="priority"
                                x-model="formData.priority"
                                required
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            >
                                <option value="low">{{ __('tasks.priority.low') }}</option>
                                <option value="medium">{{ __('tasks.priority.medium') }}</option>
                                <option value="high">{{ __('tasks.priority.high') }}</option>
                            </select>
                        </div>
                    @endif

                    @if (isset($labels['due_date_label']))
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                {{ $labels['due_date_label'] }}
                            </label>
                            <input
                                type="date"
                                name="due_date"
                                x-model="formData.due_date"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            >
                        </div>
                    @endif
                </div>

                <div class="mt-6 flex justify-end gap-2 sm:gap-3">
                    <button type="button" @click="closeModal()"
                            class="px-3 sm:px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                        {{ $labels['cancel_button'] }}
                    </button>

                    <template x-if="isEdit">
                        <button type="button"
                                @click="confirmDelete(formData.id, '{{ $labels['confirm_delete'] }}')"
                                class="px-3 sm:px-4 py-2 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-md text-sm font-medium text-red-600 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-900/40 mr-auto">
                            {{ $labels['delete_button'] }}
                        </button>
                    </template>

                    <button type="submit"
                            class="px-3 sm:px-4 py-2 bg-blue-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-blue-700">
                        {{ $labels['save_button'] }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
