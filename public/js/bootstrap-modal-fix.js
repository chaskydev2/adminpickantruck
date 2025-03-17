/**
 * Fix para el problema de inicialización de modales de Bootstrap 5
 */
document.addEventListener('DOMContentLoaded', function() {
    // Verificar si Bootstrap está disponible
    if (typeof bootstrap === 'undefined') {
        console.error('Bootstrap no está cargado');
        return;
    }
    
    // Inicializar todos los modales manualmente
    var modalElements = document.querySelectorAll('.modal');
    modalElements.forEach(function(modalElement) {
        try {
            new bootstrap.Modal(modalElement);
        } catch (error) {
            console.warn('Error al inicializar el modal:', modalElement.id, error);
        }
    });
    
    // Event listener para botones que abren modales
    document.body.addEventListener('click', function(e) {
        if (e.target && e.target.hasAttribute && e.target.hasAttribute('data-bs-toggle') && e.target.getAttribute('data-bs-toggle') === 'modal') {
            var targetSelector = e.target.getAttribute('data-bs-target');
            if (!targetSelector) return;
            
            var targetElement = document.querySelector(targetSelector);
            if (!targetElement) return;
            
            try {
                var modal = bootstrap.Modal.getInstance(targetElement) || new bootstrap.Modal(targetElement);
                modal.show();
            } catch (error) {
                console.error('Error al mostrar el modal:', targetSelector, error);
            }
            
            e.preventDefault();
        }
    }, true);
});
