export default function (type = 'company') {
    return {
        modalOpen: false,
        isEdit: false,
        formAction: '',
        previewLogo: null,
        availableUsers: [],

        formData: {
            id: null,
            name: '',
            description: '',

            logo: '',
            members: [],
            assignee_id: '',
            priority: 'medium',
            due_date: '',
            project_id: null
        },

        async fetchUsers(projectId = null) {
            this.availableUsers = [];

            try {
                let url = '/users';
                if (projectId) {
                    url += `?project_id=${projectId}`;
                }

                const response = await fetch(url, {
                    headers: { 'Accept': 'application/json' }
                });
                if (!response.ok) throw new Error('Failed to fetch users');

                const data = await response.json();
                this.availableUsers = Array.isArray(data) ? data : (data.data || []);

            } catch (error) {
                console.error('Error fetching users:', error);
            }
        },

        openCreateModal(projectId = null) {
            this.isEdit = false;
            this.resetForm();

            switch (type) {
                case 'company':
                    this.formAction = '/companies';
                    break;
                case 'project':
                    this.formAction = '/projects';
                    this.fetchUsers();
                    break;
                case 'task':
                    this.formAction = '/tasks';
                    if (projectId) {
                        this.formData.project_id = projectId;
                        this.fetchUsers(projectId)
                    }
                    break;
            }

            this.modalOpen = true;
        },

        openEditModal(id, data) {
            this.isEdit = true;
            this.formData.id = id;

            this.formData.name = data.name || '';
            this.formData.description = data.description || '';

            if (type === 'company') {
                this.formAction = `/companies/${id}`;
                this.previewLogo = data.logo || null;
                this.formData.logo = data.logo || '';
            } else if (type === 'project') {
                this.formAction = `/projects/${id}`;
                this.formData.members = data.member_ids || [];
            } else if (type === 'task') {
                this.formAction = `/tasks/${id}`;
                this.formData.assignee_id = data.assignee_id || '';
                this.formData.priority = data.priority || 'medium';
                this.formData.due_date = data.due_date || '';

                const projectId = data.project_id;
                if (projectId) {
                    this.fetchUsers(projectId);
                }
                this.formData.project_id = projectId;
            }

            this.modalOpen = true;
        },

        closeModal() {
            this.modalOpen = false;
            setTimeout(() => this.resetForm(), 300);
        },

        resetForm() {
            this.formData = {
                id: null,
                name: '',
                description: '',
                logo: '',
                members: [],
                assignee_id: '',
                priority: 'medium',
                due_date: '',
                project_id: null
            };
            this.previewLogo = null;
            this.availableUsers = [];
        },

        handleLogoUpload(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.previewLogo = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        },

        confirmDelete(id, message) {
            if (confirm(message)) {
                let url = '';
                switch (type) {
                    case 'company': url = `/companies/${id}`; break;
                    case 'project': url = `/projects/${id}`; break;
                    case 'task': url = `/tasks/${id}`; break;
                }

                const form = document.createElement('form');
                form.method = 'POST';
                form.action = url;

                const csrf = document.createElement('input');
                csrf.type = 'hidden';
                csrf.name = '_token';
                csrf.value = document.querySelector('meta[name="csrf-token"]').content;

                const method = document.createElement('input');
                method.type = 'hidden';
                method.name = '_method';
                method.value = 'DELETE';

                form.appendChild(csrf);
                form.appendChild(method);
                document.body.appendChild(form);
                form.submit();
            }
        }
    }
}
