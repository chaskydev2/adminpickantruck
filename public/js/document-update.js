/**
 * Script dedicado a la actualización de documentos
 */
document.addEventListener('DOMContentLoaded', function() {
    console.log('Document Update script loaded');
    
    // Manejar errores AJAX comunes
    function handleAjaxErrors() {
        // Interceptar respuestas de error globales para peticiones fetch
        const originalFetch = window.fetch;
        window.fetch = function() {
            return originalFetch.apply(this, arguments)
                .then(async function(response) {
                    if (!response.ok) {
                        if (response.status === 419) {
                            // Error de CSRF/sesión expirada
                            const html = await response.text();
                            if (html.includes('csrf') || html.includes('token')) {
                                console.error('CSRF token mismatch o sesión expirada');
                                alert('Su sesión ha expirado. Por favor, recargue la página.');
                                window.location.reload();
                                return Promise.reject('Sesión expirada');
                            }
                        } else if (response.status === 500) {
                            console.error('Error del servidor:', response.statusText);
                        }
                    }
                    return response;
                });
        };
    }
    
    // Función para actualizar el estado de un documento mediante formulario
    function setupDocumentStatusForms() {
        document.addEventListener('click', function(e) {
            const approveBtn = e.target.closest('.btn-approve-doc');
            const rejectBtn = e.target.closest('.btn-reject-doc');
            
            if (approveBtn) {
                e.preventDefault();
                const documentId = approveBtn.getAttribute('data-document-id');
                updateDocStatus(documentId, 'aprobado');
            }
            
            if (rejectBtn) {
                e.preventDefault();
                const documentId = rejectBtn.getAttribute('data-document-id');
                promptRejectionReason(documentId);
            }
        });
    }
    
    // Actualizar estado mediante POST con formData
    function updateDocStatus(documentId, status, comments = '') {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        // Crear formulario para enviar
        const formData = new FormData();
        formData.append('_token', csrfToken);
        formData.append('status', status);
        if (comments) formData.append('comments', comments);
        
        // Mostrar indicador de carga
        const loadingMsg = document.createElement('div');
        loadingMsg.className = 'alert alert-info document-update-status';
        loadingMsg.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Actualizando estado del documento...';
        
        const modalBody = document.querySelector('.modal-body');
        if (modalBody) {
            modalBody.prepend(loadingMsg);
        }
        
        // Realizar la petición sin headers incorrectos
        fetch(`/document/${documentId}/update-status`, {
            method: 'POST',
            body: formData
        })
        .then(response => {
            // Eliminar mensaje de carga
            document.querySelectorAll('.document-update-status').forEach(el => el.remove());
            
            if (!response.ok) {
                return response.text().then(text => {
                    console.error('Error respuesta del servidor:', text);
                    throw new Error('Error al actualizar el documento');
                });
            }
            
            // Intentar parsear respuesta como JSON
            try {
                return response.json();
            } catch (e) {
                console.log('La respuesta no es JSON, redirigiendo...');
                window.location.reload(); // Si no es JSON, simplemente recargamos la página
                return null;
            }
        })
        .then(data => {
            if (data) {
                console.log('Actualización exitosa:', data);
                
                // Mostrar mensaje de éxito
                const successMsg = document.createElement('div');
                successMsg.className = 'alert alert-success document-update-status';
                successMsg.textContent = 'Documento actualizado correctamente';
                
                if (modalBody) {
                    modalBody.prepend(successMsg);
                    
                    // Recargar datos después de 1.5 segundos
                    setTimeout(() => {
                        if (typeof loadUserDetails === 'function') {
                            const userDetailModal = document.getElementById('userDetailModal');
                            const userId = userDetailModal ? userDetailModal.getAttribute('data-user-id') : null;
                            if (userId) loadUserDetails(userId);
                        } else {
                            window.location.reload(); // Fallback: recargar página
                        }
                    }, 1500);
                }
            }
        })
        .catch(error => {
            console.error('Error al actualizar documento:', error);
            
            // Mostrar mensaje de error
            const errorMsg = document.createElement('div');
            errorMsg.className = 'alert alert-danger document-update-status';
            errorMsg.textContent = error.message || 'Error al actualizar el documento';
            
            if (modalBody) {
                modalBody.prepend(errorMsg);
                
                // Eliminar mensaje después de 5 segundos
                setTimeout(() => errorMsg.remove(), 5000);
            }
        });
    }
    
    // Pedir razón de rechazo
    function promptRejectionReason(documentId) {
        // Código prompt básico, puedes expandirlo con un modal más elaborado
        const reason = prompt('Por favor, indique el motivo del rechazo:');
        if (reason !== null) {
            updateDocStatus(documentId, 'rechazado', reason);
        }
    }
    
    // Inicializar
    handleAjaxErrors();
    setupDocumentStatusForms();
    
    // Exportar funciones para uso global
    window.documentUpdater = {
        updateStatus: updateDocStatus,
        promptRejection: promptRejectionReason
    };
});
