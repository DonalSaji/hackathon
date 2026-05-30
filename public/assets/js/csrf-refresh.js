(function () {
    function refreshCsrfToken() {
        fetch('/refresh-csrf', {
            credentials: 'include'
        })
            .then(response => response.json())
            .then(data => {
                const newToken = data.csrfToken;

                // Update <meta> tag
                const meta = document.querySelector('meta[name="csrf-token"]');
                if (meta) {
                    meta.setAttribute('content', newToken);
                }

                // Update all hidden CSRF inputs
                document.querySelectorAll('input[name="_token"]').forEach(input => {
                    input.value = newToken;
                });

                console.log('[CSRF Refresh] Token updated.');
            })
            .catch(err => {
                console.error('[CSRF Refresh] Failed to update token:', err);
            });
    }

    // Refresh on tab focus
    window.addEventListener('focus', refreshCsrfToken);

    // Refresh when tab becomes visible
    document.addEventListener('visibilitychange', () => {
        if (document.visibilityState === 'visible') {
            refreshCsrfToken();
        }
    });
})();
