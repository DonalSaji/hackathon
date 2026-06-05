let deferredPrompt;

window.addEventListener('beforeinstallprompt', (e) => {
    e.preventDefault();
    deferredPrompt = e;

    const banner = document.getElementById('pwa-install-banner');
    if (banner) {
        // show with animation
        banner.classList.add('show');
        banner.style.display = 'block';
    }
});

document.addEventListener('DOMContentLoaded', () => {
    const banner = document.getElementById('pwa-install-banner');
    const installBtn = document.getElementById('pwa-install-btn');
    const closeBtn = document.getElementById('pwa-close-btn');

    // Handle install click
    installBtn?.addEventListener('click', async () => {
        if (!deferredPrompt) return;

        deferredPrompt.prompt();
        const { outcome } = await deferredPrompt.userChoice;
        //console.log('User choice:', outcome);

        banner.classList.remove('show');
        setTimeout(() => (banner.style.display = 'none'), 300);
        deferredPrompt = null;
    });

    // Handle close click
    closeBtn?.addEventListener('click', () => {
        banner.classList.remove('show');
        setTimeout(() => (banner.style.display = 'none'), 300);
    });
});

window.addEventListener('appinstalled', () => {
    //console.log('✅ PWA Installed');
});
