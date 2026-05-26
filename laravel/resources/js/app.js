import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

document.addEventListener('DOMContentLoaded', async () => {
    if (!document.getElementById('three-container')) return;

    const { initThreeJS } = await import('./three-bg');
    initThreeJS('three-container');
});
