/**
 * Script para manejar el modal de detalles de usuario
 */
document.addEventListener('DOMContentLoaded', function() {
    console.log('Script de detalles de usuario cargado');
    
    // Botones para ver detalles de usuario
    document.querySelectorAll('.btn-info[data-bs-toggle="modal"]').forEach(function(button) {
        button.addEventListener('click', function() {
            const userId = this.getAttribute('data-user-id');
            if (!userId) {
                console.error('ID de usuario no encontrado en el botón');
                return;
            }
            
            console.log('Cargando detalles para usuario ID:', userId);
            loadUserDetails(userId);
        });
    });
    
    /**
     * Carga los detalles del usuario mediante AJAX y los muestra en el modal
     */
    function loadUserDetails(userId) {
        // Mostrar indicadores de carga
        document.getElementById('userName').textContent = 'Cargando...';
        document.getElementById('userEmail').textContent = 'Cargando...';
        document.getElementById('userPhone').textContent = 'Cargando...';
        document.getElementById('userStatus').textContent = 'Cargando...';
        document.getElementById('userCreatedAt').textContent = 'Cargando...';
        document.getElementById('userDocuments').innerHTML = '<p class="text-muted">Cargando documentos...</p>';
        
        // Obtener el token CSRF
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        // Realizar la petición para obtener detalles del usuario
        fetch(`/users/${userId}/details`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            credentials: 'same-origin'
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Error al obtener detalles del usuario');
            }
            return response.json();
        })
        .then(data => {
            console.log('Datos del usuario recibidos:', data);
            displayUserDetails(data);
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('userDocuments').innerHTML = 
                `<div class="alert alert-danger">Error al cargar los detalles. ${error.message}</div>`;
        });
    }
    
    /**
     * Muestra los detalles del usuario en el modal
     */
    function displayUserDetails(data) {
        // Información personal
        document.getElementById('userName').textContent = data.user.name || 'No disponible';
        document.getElementById('userEmail').textContent = data.user.email || 'No disponible';
        document.getElementById('userPhone').textContent = data.user.phone || 'No disponible';
        
        // Estado de verificación
        const statusElement = document.getElementById('userStatus');
        if (data.user.email_verified_at) {
            statusElement.innerHTML = '<span class="badge bg-success">Verificado</span>';
        } else {
            statusElement.innerHTML = '<span class="badge bg-warning">No Verificado</span>';
        }
        
        // Fecha de registro
        document.getElementById('userCreatedAt').textContent = data.user.formatted_date || data.user.created_at || 'No disponible';
        
        // Documentos
        const documentsContainer = document.getElementById('userDocuments');
        if (data.documents && data.documents.length > 0) {
            let documentsHtml = '<div class="list-group">';
            
            data.documents.forEach(doc => {
                let statusBadge = '';
                switch(doc.status) {
                    case 'aprobado':
                        statusBadge = '<span class="badge bg-success">Aprobado</span>';
                        break;
                    case 'rechazado':
                        statusBadge = '<span class="badge bg-danger">Rechazado</span>';
                        break;
                    default:
                        statusBadge = '<span class="badge bg-warning">Pendiente</span>';
                }
                
                documentsHtml += `
                <div class="list-group-item">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">${doc.name}</h6>
                        ${statusBadge}
                    </div>
                    <p class="text-muted mb-0 small">${doc.document_type || 'Documento'}</p>
                    <div class="mt-2">
                        <a href="/document/${doc.id}" target="_blank" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-eye me-1"></i> Ver
                        </a>
                        <button class="btn btn-sm btn-outline-success ms-1 btn-approve-doc" data-document-id="${doc.id}" ${doc.status === 'aprobado' ? 'disabled' : ''}>
                            <i class="fas fa-check me-1"></i> Aprobar
                        </button>
                        <button class="btn btn-sm btn-outline-danger ms-1 btn-reject-doc" data-document-id="${doc.id}" ${doc.status === 'rechazado' ? 'disabled' : ''}>
                            <i class="fas fa-times me-1"></i> Rechazar
                        </button>
                    </div>
                </div>`;
            });
            
            documentsHtml += '</div>';
            documentsContainer.innerHTML = documentsHtml;
            
            // Configurar botones de aprobación/rechazo de documentos
            setupDocumentActions();
        } else {
            documentsContainer.innerHTML = '<p class="text-muted">Este usuario no tiene documentos.</p>';
        }
    }
    
    /**
     * Configura los botones de acción para aprobar o rechazar documentos
     */
    function setupDocumentActions() {
        // Botones de aprobar documento
        document.querySelectorAll('.btn-approve-doc').forEach(btn => {
            btn.addEventListener('click', function() {
                const documentId = this.getAttribute('data-document-id');
                updateDocumentStatus(documentId, 'aprobado');
            });
        });
        
        // Botones de rechazar documento
        document.querySelectorAll('.btn-reject-doc').forEach(btn => {
            btn.addEventListener('click', function() {
                const documentId = this.getAttribute('data-document-id');
                showRejectModal(documentId);
            });
        });
    }
    
    /**
     * Muestra el modal para rechazar un documento con comentarios
     */
    function showRejectModal(documentId) {
        // Implementar si es necesario
        console.log('Mostrar modal para rechazar documento ID:', documentId);
    }
    
    /**
     * Actualiza el estado de un documento
     */
    function updateDocumentStatus(documentId, status, comments = '') {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        // Mostrar indicador de carga en el botón
        const button = document.querySelector(`.btn-approve-doc[data-document-id="${documentId}"]`);
        const originalText = button ? button.innerHTML : '';
        
        if (button) {
            button.disabled = true;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Procesando...';
        }
        
        // Crear correctamente el FormData
        const formData = new FormData();
        formData.append('_token', csrfToken);
        formData.append('status', status);
        formData.append('comments', comments || '');
        
        fetch(`/document/${documentId}/update-status`, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                if (response.status === 419) {
                    throw new Error('Sesión expirada. Por favor recargue la página e intente de nuevo.');
                }
                return response.text().then(text => {
                    console.error("Respuesta del servidor:", text);
                    throw new Error(`Error del servidor: ${response.status}`);
                });
            }
            return response.json();
        })
        .then(data => {
            console.log('Documento actualizado:', data);
            // Recargar los detalles del usuario para reflejar el cambio
            const userDetailModal = document.getElementById('userDetailModal');
            const userId = userDetailModal.getAttribute('data-user-id');
            if (userId) {
                loadUserDetails(userId);
            }
            
            // Mostrar mensaje de éxito temporal
            const documentsContainer = document.getElementById('userDocuments');
            const successAlert = document.createElement('div');
            successAlert.className = 'alert alert-success mt-2';
            successAlert.textContent = 'Documento actualizado exitosamente';
            documentsContainer.prepend(successAlert);
            
            // Eliminar alerta después de 3 segundos
            setTimeout(() => successAlert.remove(), 3000);
        })
        .catch(error => {
            console.error('Error:', error);
            
            // Mostrar mensaje de error en el contenedor de documentos
            const documentsContainer = document.getElementById('userDocuments');
            const errorAlert = document.createElement('div');
            errorAlert.className = 'alert alert-danger mt-2';
            errorAlert.textContent = `Error al actualizar el documento: ${error.message}`;
            documentsContainer.prepend(errorAlert);
            
            // Eliminar alerta después de 5 segundos
            setTimeout(() => errorAlert.remove(), 5000);
        })
        .finally(() => {
            // Restaurar estado del botón
            if (button) {
                button.disabled = false;
                button.innerHTML = originalText;
            }
        });
    }
});
