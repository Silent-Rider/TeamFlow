import './bootstrap';
import './update-profile';
import Alpine from 'alpinejs';
import taskManager from './task-manager';

window.Alpine = Alpine;

Alpine.data('taskManager', taskManager);
Alpine.start();
