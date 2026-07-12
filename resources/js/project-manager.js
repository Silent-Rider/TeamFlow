import modalManager from './modal-manager';
export default function () {
    const modal = modalManager('project');
    return {
        ...modal
    }
}
