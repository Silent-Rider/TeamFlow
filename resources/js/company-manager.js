export default function () {
    return {
        modalOpen: false,
        isEdit: false,
        formAction: '',
        previewLogo: null,
        formData: {
            id: null,
            name: '',
            description: '',
            logo: ''
        },

        openModal() {
            this.isEdit = false;
            this.formAction = '/companies';
            this.resetForm();
            this.modalOpen = true;
        },

        editCompany(id, name, description, logoUrl) {
            this.isEdit = true;
            this.formAction = `/companies/${id}`;
            this.formData = {
                id,
                name,
                description,
                logo: logoUrl
            };
            this.previewLogo = logoUrl;
            this.modalOpen = true;
        },

        closeModal() {
            this.modalOpen = false;
            setTimeout(() => this.resetForm(), 300);
        },

        resetForm() {
            this.formData = { id: null, name: '', description: '', logo: '' };
            this.previewLogo = null;
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
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/companies/${id}`;

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
        },

        copyCode(code) {
            const markCopied = () => {
                this.copied = true;
                setTimeout(() => this.copied = false, 1000);
            };

            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(code).then(markCopied).catch(() => this.fallbackCopy(code, markCopied));
            } else {
                this.fallbackCopy(code, markCopied);
            }
        },

        fallbackCopy(text, cb) {
            const ta = document.createElement('textarea');
            ta.value = text;
            ta.style.position = 'fixed';
            ta.style.opacity = '0';
            document.body.appendChild(ta);
            ta.focus();
            ta.select();
            try { document.execCommand('copy'); cb(); } catch (e) {}
            document.body.removeChild(ta);
        }
    }
}
