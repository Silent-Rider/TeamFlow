import modalManager from './modal-manager';
export default function () {
    const manager = modalManager('project');
    return {
        ...manager,

        openEditModal(id, name, description, memberIds) {
            this.openEditModal(id, { name, description, member_ids: memberIds });
        }
    }
}
