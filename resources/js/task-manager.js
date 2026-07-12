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
            const taskElements = document.querySelectorAll('[data-task-id][data-is-done]');

            taskElements.forEach(el => {
                this.tasksStatus[el.dataset.taskId] = el.dataset.isDone === '1';
            });

            this.updateStats();

            this.$nextTick(() => {
                this.bottomNav = document.getElementById('bottom-nav');
                this.mainContent = document.querySelector('main');
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
            if(event) event.preventDefault();
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

            const scrollContainer = document.getElementById('task-scroll-area');
            if (scrollContainer) this.savedScrollTop = scrollContainer.scrollTop;

            this.currentTaskId = id;
            this.detailsOpen = true;

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
                    if(chatContainer && chatContainer !== scrollContainer) {
                        chatContainer.scrollTop = chatContainer.scrollHeight;
                    }
                });

            } catch (error) {
                console.error('Error loading details:', error);
                this.detailsHtml = '<div class="p-4 text-red-500 text-center">Ошибка загрузки</div>';
            }
        },

        closeTaskDetails() {
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

            const tempId = 'temp-' + Date.now();
            const optimisticHtml = `
                <div id="${tempId}" class="bg-blue-50 dark:bg-blue-900/20 p-3 rounded-lg opacity-70 animate-pulse">
                    <div class="flex justify-between text-xs text-gray-500 mb-1">
                        <span>Вы (только что)</span>
                        <span>Отправка...</span>
                    </div>
                    <p>${this.newCommentText.replace(/\n/g, '<br>')}</p>
                </div>
            `;

            const commentsContainer = this.$el.querySelector('.space-y-4');
            if(commentsContainer) {
                commentsContainer.insertAdjacentHTML('beforeend', optimisticHtml);
                commentsContainer.scrollTop = commentsContainer.scrollHeight;
            }

            const textToSend = this.newCommentText;
            this.newCommentText = '';

            try {
                const response = await fetch(`/tasks/${taskId}/comments`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ content: textToSend })
                });

                if (!response.ok) throw new Error('Failed to send comment');

                const data = await response.json();

                const tempEl = document.getElementById(tempId);
                if (tempEl) {
                    tempEl.outerHTML = data.comment_html;
                } else if(commentsContainer) {
                    commentsContainer.insertAdjacentHTML('beforeend', data.comment_html);
                }

            } catch (error) {
                console.error('Error sending comment:', error);
                alert('Не удалось отправить комментарий');
            }
        }
    }
}
