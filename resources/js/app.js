import './bootstrap';

import Alpine from 'alpinejs';

import { initializeChannel } from './channel';
import './messages';

// Initialiser uniquement si on est sur une page de channel
if (document.querySelector('.channel-page')) {
    initializeChannel();
}

window.Alpine = Alpine;

Alpine.start();
