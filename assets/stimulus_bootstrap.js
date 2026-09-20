import { startStimulusApp } from '@symfony/stimulus-bundle';
import FatrController from './controllers/fatr_controller.js';

const app = startStimulusApp();
app.register('fatr', FatrController);
