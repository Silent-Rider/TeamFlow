<x-app-layout>
    <div class="py-0 sm:py-12 h-[calc(100vh-4.05rem)]" x-data="companyManager()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 h-full flex flex-col">

            @if (session('status'))
                <div
                    x-data="{ show: true }"
                    x-init="setTimeout(() => show = false, 4000)"
                    x-show="show"
                    x-transition:leave="transition ease-in duration-300"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 -translate-y-2"
                    class="fixed top-4 right-4 z-50 bg-green-600 text-white px-5 py-3 rounded-lg shadow-lg text-sm font-medium"
                >
                    {{ __('admin.success_message') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg flex-1 flex flex-col min-h-0">

                <div
                    class="p-6 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center bg-white dark:bg-gray-800 z-10 shrink-0">
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">{{ __('admin.title') }}</h2>
                    <button @click="openCreateModal()" class="btn-primary text-sm py-2 px-4">
                        {{ __('admin.add_button') }}
                    </button>
                </div>

                <div class="flex-1 overflow-y-auto custom-scrollbar p-6">
                    <ul class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($companies as $company)
                            <li class="flex items-center gap-4 py-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 rounded-lg px-4 -mx-4 transition-colors group cursor-pointer"
                                @click="openEditModal({{ $company->id }}, '{{ $company->name }}', '{{ $company->description }}', '{{ $company->logo ? Storage::url($company->logo) : '' }}')">

                                <div
                                    class="h-12 w-12 rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center overflow-hidden shrink-0 border border-gray-200 dark:border-gray-600">
                                    @if($company->logo && Storage::disk('public')->exists($company->logo))
                                        <img src="{{ Storage::url($company->logo) }}"
                                             class="h-full w-full object-cover">
                                    @else
                                        <x-avatar-placeholder
                                            :name="$company->name"
                                            class="h-full w-full text-2xl border-2 border-white/20 shadow-sm rounded-lg"
                                        />
                                    @endif
                                </div>

                                <div class="flex-1 min-w-0">
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 truncate">{{ $company->name }}</h3>
                                    <div class="flex items-center gap-2 mt-1" x-data="{ copied: false }">
                                        <span
                                            @click.stop
                                            @mousedown.stop
                                            class="inline-flex items-center px-2 py-0.5 rounded text-sm font-mono font-semibold bg-gray-100 dark:bg-gray-700
                                            text-gray-600 dark:text-gray-300 border border-gray-200 dark:border-gray-600 tracking-wider cursor-text">
                                            {{ $company->code }}
                                        </span>
                                        <button
                                            type="button"
                                            @click.stop="copyCode('{{ $company->code }}')"
                                            :class="copied ? 'text-green-500' : 'text-gray-400 hover:text-blue-500'"
                                            class="relative w-6 h-6 flex items-center justify-center transition-colors rounded shrink-0 active:scale-95 touch-manipulation"
                                            title="{{ __('admin.copy_code') }}"
                                        >
                                            <svg x-show="!copied" x-transition class="absolute w-5 h-5" fill="none"
                                                 stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0
                                                    002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                            </svg>
                                            <svg x-show="copied" x-transition class="absolute w-5 h-5" fill="none"
                                                 stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </button>
                                    </div>

                                    <p class="text-sm text-gray-500 dark:text-gray-400 truncate mt-1">{{ Str::limit($company->description, 150) }}</p>
                                </div>

                                <div class="opacity-0 group-hover:opacity-100 transition-opacity">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                         viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M9 5l7 7-7 7"/>
                                    </svg>
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

        <x-modal-window :type="'company'"/>
    </div>
</x-app-layout>
