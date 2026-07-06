<x-app-layout>
    <div class="py-12 h-[calc(100vh-4rem)]" x-data="companyManager()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 h-full flex flex-col">

            @if (session('status'))
                <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show"
                     class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative shrink-0">
                    {{ __('admin.success_message') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg flex-1 flex flex-col min-h-0">

                <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center bg-white dark:bg-gray-800 z-10 shrink-0">
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">{{ __('admin.title') }}</h2>
                    <button @click="openModal()" class="btn-primary text-sm py-2 px-4">
                        {{ __('admin.add_button') }}
                    </button>
                </div>

                <div class="flex-1 overflow-y-auto custom-scrollbar p-6">
                    <ul class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($companies as $company)
                            <li class="flex items-center gap-4 py-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 rounded-lg px-4 -mx-4 transition-colors group cursor-pointer"
                                @click="editCompany({{ $company->id }}, '{{ $company->name }}', '{{ $company->description }}', '{{ $company->logo ? Storage::url($company->logo) : '' }}')">

                                <div class="h-12 w-12 rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center overflow-hidden shrink-0 border border-gray-200 dark:border-gray-600">
                                    @if($company->logo)
                                        <img src="{{ Storage::url($company->logo) }}" class="h-full w-full object-cover">
                                    @else
                                        <span class="text-xl font-bold text-gray-400">{{ mb_substr($company->name, 0, 1) }}</span>
                                    @endif
                                </div>

                                <div class="flex-1 min-w-0">
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 truncate">{{ $company->name }}</h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 truncate">{{ Str::limit($company->description, 50) }}</p>
                                </div>

                                <div class="opacity-0 group-hover:opacity-100 transition-opacity">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </div>
                            </li>
                        @empty
                            <li class="py-8 text-center text-gray-500">{{ __('admin.empty_list') }}</li>
                        @endforelse
                    </ul>

                    <div class="mt-6">
                        {{ $companies->links() }}
                    </div>
                </div>
            </div>
        </div>

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

                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white"
                                x-text="isEdit ? '{{ __('admin.edit_title') }}' : '{{ __('admin.create_title') }}'"></h3>
                            <button type="button" @click="closeModal()" class="text-gray-400 hover:text-gray-500">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                            </button>
                        </div>

                        <div class="mb-6 flex justify-center">
                            <div class="relative h-24 w-24 rounded-full bg-gray-100 dark:bg-gray-700 border-2 border-dashed border-gray-300 dark:border-gray-600 flex items-center justify-center overflow-hidden cursor-pointer hover:border-blue-500 transition-colors">
                                <img x-show="previewLogo" :src="previewLogo" class="h-full w-full object-cover">
                                <div x-show="!previewLogo" class="text-gray-400 flex flex-col items-center">
                                    <svg class="w-8 h-8 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <span class="text-xs">{{ __('admin.logo_placeholder') }}</span>
                                </div>
                                <input type="file" name="logo" accept=".jpg,.jpeg,.png,.webp" class="absolute inset-0 opacity-0 cursor-pointer" @change="handleLogoUpload">
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('admin.name_label') }}</label>
                                <input type="text" name="name" x-model="formData.name" required
                                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('admin.description_label') }}</label>
                                <textarea name="description" x-model="formData.description" rows="3"
                                          class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"></textarea>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end gap-3">
                            <button type="button" @click="closeModal()"
                                    class="px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                                {{ __('admin.cancel_button') }}
                            </button>

                            <template x-if="isEdit">
                                <button type="button"
                                        @click="confirmDelete(formData.id, '{{ __('admin.confirm_delete') }}')"
                                        class="px-4 py-2 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-md text-sm font-medium text-red-600 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-900/40 mr-auto">
                                    {{ __('admin.delete_button') }}
                                </button>
                            </template>

                            <button type="submit"
                                    class="px-4 py-2 bg-blue-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-blue-700">
                                {{ __('admin.save_button') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
