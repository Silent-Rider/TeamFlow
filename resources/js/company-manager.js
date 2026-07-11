import modalManager from './modal-manager';
export default function () {
    const manager = modalManager('company');

    return {
        ...manager,

        copied: false,

        editCompany(id, name, description, logoUrl) {
            this.editItem(id, { name, description, logo: logoUrl });
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
