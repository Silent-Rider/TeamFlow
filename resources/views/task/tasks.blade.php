<x-app-layout>
    <div class="py-0 sm:py-12 h-[calc(100vh-4.05rem)]" x-data="taskManager()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 h-full">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg h-full flex transition-all duration-300 ease-in-out">

                <div id="task-list-wrapper"
                     class="flex-1 min-w-0 transition-all duration-300 flex flex-col h-full relative"
                     :class="detailsOpen ? 'lg:w-3/4 w-full' : 'w-full'"
                     x-ref="taskListContainer">
                    @include('task.partials.task-list', [
                        'tasks' => $tasks,
                        'filter' => request('filter', 'all'),
                        'embedded' => false,
                    ])
                </div>

                @include('task.partials.task-details-panel')
            </div>
        </div>

        <x-modal-window :type="'task'"/>
    </div>
</x-app-layout>
