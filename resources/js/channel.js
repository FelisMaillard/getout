export function initializeChannel() {
    function handleMobileNavbar() {
        const navbar = document.querySelector('.channel-navbar');
        if (navbar) {
            navbar.style.display = 'none';
        }
    }

    // Rétablir la navbar quand on quitte le channel
    window.addEventListener('beforeunload', function() {
        const navbar = document.querySelector('.channel-navbar');
        if (navbar) {
            navbar.style.display = 'block';
        }
    });

    // Exécuter au chargement
    handleMobileNavbar();
}
