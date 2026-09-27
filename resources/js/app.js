import './bootstrap';
import './firebase';
import { createIcons, icons } from 'lucide/dist/esm/lucide.mjs';

document.addEventListener('DOMContentLoaded', () => {
    createIcons({ icons });
});
