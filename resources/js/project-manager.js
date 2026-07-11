import modalManager from './modal-manager';
export default function () {
    const manager = modalManager('project');
    return {
        ...manager,

        editProject(id, name, description, memberIds) {
            this.editItem(id, { name, description, member_ids: memberIds });
        }
    }
}
