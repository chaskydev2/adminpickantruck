/**
 * Script principal para funcionalidades generales del sitio
 */
document.addEventListener('DOMContentLoaded', function() {
    // Tooltip básico para elementos con title
    document.querySelectorAll('[title]').forEach(element => {
        if (window.bootstrap && bootstrap.Tooltip) {
            new bootstrap.Tooltip(element);
        }
    });

    // Destacar el logo cuando se está en la página del dashboard
    if (window.location.pathname === '/dashboard' || window.location.pathname === '/') {
        const logoContainer = document.querySelector('.logo-container');
        if (logoContainer) {
            logoContainer.classList.add('active-page');
        }
    }
});
