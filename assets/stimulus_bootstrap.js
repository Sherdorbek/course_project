import { startStimulusApp } from '@symfony/stimulus-bundle';
import SatrController from './controllers/satr_controller.js';

const app = startStimulusApp();
app.register('satr', SatrController);
