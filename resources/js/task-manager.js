import modalManager from './modal-manager';

export default function () {
    const modal = modalManager('task');
    return {
        ...modal,

        detailsOpen: false,
        detailsHtml: '',
        tasksStatus: {},

        totalTasks: 0,
        doneTasks: 0,

        currentTaskId: null,
        currentTaskName: '',
        newCommentText: '',
        selectedFile: '',
        savedScrollTop: 0,
        bottomNav: document.getElementById('bottom-nav'),
        mainContent: document.querySelector('main'),

        init() {
            this.scanTasks(this.$refs.taskListContainer || this.$el);
            this.updateStats();

            this.$nextTick(() => {
                this.bottomNav = document.getElementById('bottom-nav');
                this.mainContent = document.querySelector('main');
            });

            this.$watch('$store.projectPanel.html', () => {
                this.closeTaskDetails();

                this.tasksStatus = {};

                this.$nextTick(() => {
                    if (this.$refs.taskListContainer) {
                        window.Alpine.initTree(this.$refs.taskListContainer);
                        this.scanTasks(this.$refs.taskListContainer);
                    }
                    this.updateStats();
                });
            });
        },

        scanTasks(container) {
            if (!container) return;
            container.querySelectorAll('[data-task-id][data-is-done]').forEach(el => {
                this.tasksStatus[el.dataset.taskId] = el.dataset.isDone === '1';
            });
        },

        updateStats() {
            const statuses = Object.values(this.tasksStatus);
            this.totalTasks = statuses.length;
            this.doneTasks = statuses.filter(status => status).length;
        },

        isTaskDone(id) {
            return !!this.tasksStatus[id];
        },

        async toggleTask(id, event, filter) {
            if (event) event.preventDefault();
            const previousState = this.tasksStatus[id];

            this.tasksStatus[id] = !previousState;
            this.updateStats();

            try {
                const response = await fetch(`/tasks/${id}/toggle`, {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({})
                });

                if (!response.ok) throw new Error('Network response was not ok');
                const data = await response.json();

                this.tasksStatus[id] = data.is_done;
                this.updateStats();

                if (filter !== 'all') {
                    const taskElement = document.querySelector(`button[data-task-id="${id}"]`).closest('li');
                    if (taskElement) {
                        taskElement.style.transition = 'opacity 0.3s, transform 0.3s';
                        taskElement.style.opacity = '0';
                        taskElement.style.transform = 'translateX(-100%)';
                        setTimeout(() => taskElement.remove(), 300);
                    }
                    if (this.currentTaskId === id) {
                        this.detailsOpen = false;
                    }
                }
            } catch (error) {
                console.error('Error toggling task:', error);
                this.tasksStatus[id] = previousState;
                this.updateStats();
            }
        },

        async openTaskDetails(id) {
            if (this.currentTaskId === id && this.detailsOpen) return;

            const scrollContainer = document.getElementById('task-scroll-area') || this.$refs.taskListContainer;
            if (scrollContainer) this.savedScrollTop = scrollContainer.scrollTop;

            this.currentTaskId = id;
            this.detailsOpen = true;
            this.subscribeToTask(this.currentTaskId);

            this.newCommentText = '';
            this.selectedFile = null;
            if (window.selectedFileName) window.selectedFileName = '';

            if (this.bottomNav) this.bottomNav.style.display = 'none';
            document.body.classList.add('overflow-hidden');

            this.detailsHtml = '<div class="flex justify-center py-10"><div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div></div>';

            try {
                const response = await fetch(`/tasks/${id}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (!response.ok) throw new Error('Failed to load task details');
                const data = await response.json();

                this.currentTaskName = data.task.name;
                this.detailsHtml = data.html;

                this.$nextTick(() => {
                    if (scrollContainer) scrollContainer.scrollTop = this.savedScrollTop;

                    const chatContainer = document.querySelector('.custom-scrollbar');
                    if (chatContainer && chatContainer !== scrollContainer) {
                        chatContainer.scrollTop = chatContainer.scrollHeight;
                    }
                });

            } catch (error) {
                console.error('Error loading details:', error);
                this.detailsHtml = '<div class="p-4 text-red-500 text-center">Ошибка загрузки</div>';
            }
        },

        closeTaskDetails() {
            this.unsubscribeFromTask();
            this.detailsOpen = false;

            if (this.bottomNav) this.bottomNav.style.display = 'flex';
            document.body.classList.remove('overflow-hidden');

            this.currentTaskId = null;
            this.newCommentText = '';
            this.selectedFile = null;
            if (window.selectedFileName) window.selectedFileName = '';
        },

        async addComment(taskId) {
            if (!this.newCommentText.trim() || !taskId) return;

            const textToSend = this.newCommentText;
            this.newCommentText = '';

            try {
                const response = await fetch(`/tasks/${taskId}/comments`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ content: textToSend })
                });

                if (!response.ok) throw new Error('Failed to send comment');
            } catch (error) {
                console.error('Error sending comment:', error);
                alert('Не удалось отправить комментарий');
                this.newCommentText = textToSend;
            }
        },

        subscribeToTask(taskId) {
            this.unsubscribeFromTask();
            window.Echo.private(`task.${taskId}`)
                .listen('.comment.created', (e) => {
                    this.detailsHtml += e.html;
                    this.$nextTick(() => {
                        const noComments = document.getElementById('task_no_comments');
                        if (noComments) {
                            noComments.remove();
                        }
                        const chatContainer = document.getElementById('task-chat-container');

                        if (chatContainer) {
                            setTimeout(() => {
                                chatContainer.scrollTop = chatContainer.scrollHeight;
                            }, 50);
                        }
                    });
                });
        },

        unsubscribeFromTask() {
            if (this.currentTaskId) {
                window.Echo.leave(`task.${this.currentTaskId}`);
            }
        }
    };
}
