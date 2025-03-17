/**
 * Fix específico para los modales en la página de documentos requeridos
 */
document.addEventListener('DOMContentLoaded', function() {
    console.log('Document Modal Fix script loaded');
    
    // Inicialización manual de los modales en la página de documentos
    function initializeModals() {
        // Inicializar cada modal manualmente con configuraciones específicas
        document.querySelectorAll('.modal').forEach(function(modal) {
            // Antes de inicializar, eliminamos instancias antiguas si existen
            if (bootstrap.Modal.getInstance(modal)) {
                bootstrap.Modal.getInstance(modal).dispose();
            }
            
            try {
                // Opciones explícitas para prevenir el error de backdrop
                const options = {
                    backdrop: true,
                    keyboard: true,
                    focus: true
                };
                
                // Crear nueva instancia
                new bootstrap.Modal(modal, options);
                console.log(`Modal ${modal.id} inicializado correctamente`);
            } catch (error) {
                console.error(`Error al inicializar modal ${modal.id}:`, error);
            }
        });
    }
    
    // Configuración para los botones de editar documento
    function setupEditButtons() {
        document.querySelectorAll('[data-bs-toggle="modal"][data-bs-target^="#editDocumentModal"]').forEach(function(button) {
            button.addEventListener('click', function(event) {
                event.preventDefault();
                
                const modalId = this.getAttribute('data-bs-target');
                const modalElement = document.querySelector(modalId);
                
                if (modalElement) {
                    try {
                        // Asegurarse de que no haya backdrops residuales antes de abrir
                        document.querySelectorAll('.modal-backdrop').forEach(backdrop => backdrop.remove());
                        document.body.classList.remove('modal-open');
                        document.body.style.overflow = '';
                        document.body.style.paddingRight = '';
                        
                        // Obtener o crear instancia del modal
                        const modalInstance = bootstrap.Modal.getInstance(modalElement) || 
                                            new bootstrap.Modal(modalElement, {
                                                backdrop: true,
                                                keyboard: true,
                                                focus: true
                                            });
                        
                        // Mostrar el modal
                        modalInstance.show();
                    } catch (error) {
                        console.error('Error al mostrar el modal:', error);
                    }
                } else {
                    console.error(`Modal ${modalId} no encontrado`);
                }
            });
        });
    }
    
    // Configuración para los botones de eliminar documento
    function setupDeleteButtons() {
        document.querySelectorAll('[data-bs-toggle="modal"][data-bs-target^="#deleteDocumentModal"]').forEach(function(button) {
            button.addEventListener('click', function(event) {
                event.preventDefault();
                
                const modalId = this.getAttribute('data-bs-target');
                const modalElement = document.querySelector(modalId);
                
                if (modalElement) {
                    try {
                        // Asegurarse de que no haya backdrops residuales
                        document.querySelectorAll('.modal-backdrop').forEach(backdrop => backdrop.remove());
                        
                        // Mostrar el modal
                        const modalInstance = bootstrap.Modal.getInstance(modalElement) || 
                                            new bootstrap.Modal(modalElement, {
                                                backdrop: true,
                                                keyboard: true,
                                                focus: true
                                            });
                        modalInstance.show();
                    } catch (error) {
                        console.error('Error al mostrar el modal:', error);
                    }
                }
            });
        });
    }
    
    // Configuración para el botón de crear documento
    function setupCreateButton() {
        const createButton = document.querySelector('[data-bs-target="#createDocumentModal"]');
        if (createButton) {
            createButton.addEventListener('click', function(event) {
                event.preventDefault();
                
                const modalElement = document.getElementById('createDocumentModal');
                if (modalElement) {
                    try {
                        // Limpiar backdrops residuales
                        document.querySelectorAll('.modal-backdrop').forEach(backdrop => backdrop.remove());
                        
                        // Mostrar el modal
                        const modalInstance = bootstrap.Modal.getInstance(modalElement) || 
                                            new bootstrap.Modal(modalElement, {
                                                backdrop: true,
                                                keyboard: true,
                                                focus: true
                                            });
                        modalInstance.show();
                    } catch (error) {
                        console.error('Error al mostrar el modal de creación:', error);
                    }
                }
            });
        }
    }
    
    // Configuración para los eventos de cierre de modales
    function setupModalCleanup() {
        document.querySelectorAll('.modal').forEach(function(modal) {
            // Cuando el modal se ha ocultado completamente
            modal.addEventListener('hidden.bs.modal', function() {
                // Limpiar los backdrops residuales
                setTimeout(function() {
                    document.querySelectorAll('.modal-backdrop').forEach(function(backdrop) {
                        backdrop.remove();
                    });
                    
                    // Solo restaurar estos estilos si no hay más modales visibles
                    if (!document.querySelector('.modal.show')) {
                        document.body.classList.remove('modal-open');
                        document.body.style.overflow = '';
                        document.body.style.paddingRight = '';
                    }
                }, 100);
            });
        });
    }
    
    // Ejecutar todas las funciones de inicialización
    setTimeout(function() {
        initializeModals();
        setupEditButtons();
        setupDeleteButtons();
        setupCreateButton();
        setupModalCleanup();
    }, 300); // Un pequeño retraso para asegurarnos de que el DOM esté completamente cargado
});
