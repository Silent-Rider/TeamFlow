import modalManager from './modal-manager';

export const projectPanelStore = {
    open: false,
    projectId: null,
    projectName: '',
    filter: 'all',
    html: '',
    loading: false,

    async select(id, name) {
        this.projectId = id;
        this.projectName = name;
        this.filter = 'all';
        this.open = true;
        await this.fetchTasks();
    },

    async changeFilter(filter) {
        this.filter = filter;
        await this.fetchTasks();
    },

    async fetchTasks() {
        this.loading = true;
        try {
            const response = await fetch(`/tasks?project_id=${this.projectId}&filter=${this.filter}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) throw new Error('Failed to load project tasks');
            const data = await response.json();
            this.html = data.html;
        } catch (error) {
            console.error('Error loading project tasks:', error);
            this.html = '<div class="p-4 text-red-500 text-center">Ошибка загрузки</div>';
        } finally {
            this.loading = false;
        }
    },

    close() {
        this.open = false;
        this.projectId = null;
        this.projectName = '';
        this.filter = 'all';
        this.html = '';
    }
};

export default function () {
    const modal = modalManager('project');
    return {
        ...modal
    };
}
