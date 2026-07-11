import './bootstrap';
import Alpine from 'alpinejs';
import './update-profile';
import companyManager from "./company-manager";
import projectManager from "./project-manager";
import taskManager from './task-manager';

window.Alpine = Alpine;

Alpine.data('companyManager', companyManager)
Alpine.data('projectManager', projectManager)
Alpine.data('taskManager', taskManager);
Alpine.start();
