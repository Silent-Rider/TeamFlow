import './bootstrap';
import './update-profile';
import Alpine from 'alpinejs';
import taskManager from './task-manager';
import companyManager from "./company-manager";

window.Alpine = Alpine;

Alpine.data('taskManager', taskManager);
Alpine.data('companyManager', companyManager)
Alpine.start();
