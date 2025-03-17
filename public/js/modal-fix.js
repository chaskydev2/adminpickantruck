/**
 * Fix para problemas con modales de Bootstrap 5
 * - Mantiene el backdrop visible mientras el modal está abierto
 * - Asegura que el backdrop desaparezca correctamente al cerrar
 */
document.addEventListener('DOMContentLoaded', function() {
    // Verificar si Bootstrap está disponible
    if (typeof bootstrap === 'undefined') {
        console.error('Bootstrap no está cargado');
        return;
    }
    
    console.log('Modal Fix script cargado correctamente');
    
    // Inicializar todos los modales con backdrop habilitado
    var modalElements = document.querySelectorAll('.modal');
    
    modalElements.forEach(function(modalElement) {
        try {
            // Configurar el modal para mostrar backdrop (fondo oscuro)
            var modalInstance = new bootstrap.Modal(modalElement, {
                backdrop: true,  // Esto hace que se muestre el fondo oscuro
                keyboard: true,
                focus: true
            });
            
            // Evento para manejar el cierre correcto del modal
            modalElement.addEventListener('hidden.bs.modal', function () {
                // Esperar un momento y luego verificar si hay backdrops residuales
                setTimeout(function() {
                    // Si no hay modales visibles, limpiamos cualquier backdrop residual
                    if (!document.querySelector('.modal.show')) {
                        const backdrops = document.querySelectorAll('.modal-backdrop');
                        backdrops.forEach(backdrop => {
                            backdrop.remove();
                        });
                        
                        // Restaurar estilos del body
                        document.body.classList.remove('modal-open');
                        document.body.style.removeProperty('overflow');
                        document.body.style.removeProperty('padding-right');
                    }
                }, 150);
            });
            
        } catch (error) {
            console.warn('Error al inicializar el modal:', modalElement.id, error);
        }
    });
    
    // Corregir el problema de múltiples backdrops cuando se abren varios modales
    document.querySelectorAll('[data-bs-toggle="modal"]').forEach(function(button) {
        button.addEventListener('click', function() {
            var targetSelector = this.getAttribute('data-bs-target');
            var targetModal = document.querySelector(targetSelector);
            
            if (targetModal) {
                // Si hay otro modal abierto, cerrarlo primero
                document.querySelectorAll('.modal.show').forEach(function(openModal) {
                    if (openModal !== targetModal) {
                        var openModalInstance = bootstrap.Modal.getInstance(openModal);
                        if (openModalInstance) {
                            openModalInstance.hide();
                        }
                    }
                });
            }
        });
    });
});

// Asegurar limpieza al cargar o refrescar la página
window.addEventListener('load', function() {
    // Limpiar cualquier backdrop residual
    const backdrops = document.querySelectorAll('.modal-backdrop');
    backdrops.forEach(backdrop => {
        backdrop.remove();
    });
    document.body.classList.remove('modal-open');
    document.body.style.overflow = '';
    document.body.style.paddingRight = '';
});
