/**
 * Script específico para la página de usuarios que maneja modales y verificación de documentos
 */
document.addEventListener('DOMContentLoaded', function() {
    console.log('Script de usuarios cargado correctamente');
    
    // Objeto para almacenar instancias de modales
    const modalInstances = {};
    
    // Inicializar los modales de la página
    function initModals() {
        document.querySelectorAll('.modal').forEach(function(modal) {
            try {
                modalInstances[modal.id] = new bootstrap.Modal(modal, {
                    backdrop: true,
                    keyboard: true
                });
                
                console.log(`Modal ${modal.id} inicializado`);
                
                // Evento para cuando el modal se oculta
                modal.addEventListener('hidden.bs.modal', function() {
                    console.log(`Modal ${modal.id} oculto`);
                    cleanupBackdrops();
                });
            } catch (e) {
                console.error(`Error al inicializar modal ${modal.id}:`, e);
            }
        });
    }
    
    // Función para mostrar un modal específico
    function showModal(modalId, callback) {
        console.log('Intentando mostrar modal:', modalId);
        
        const modalElement = document.getElementById(modalId);
        if (!modalElement) {
            console.error(`Modal ${modalId} no encontrado en el DOM`);
            return;
        }
        
        // Intentar obtener la instancia existente o crear una nueva
        let modalInstance = modalInstances[modalId];
        if (!modalInstance) {
            try {
                modalInstance = new bootstrap.Modal(modalElement);
                modalInstances[modalId] = modalInstance;
            } catch (e) {
                console.error(`Error al crear instancia para modal ${modalId}:`, e);
                return;
            }
        }
        
        // Mostrar el modal
        try {
            modalInstance.show();
            console.log(`Modal ${modalId} mostrado con instancia ${modalInstance ? 'global' : 'local'}`);
            
            if (typeof callback === 'function') {
                callback();
            }
        } catch (e) {
            console.error(`Error al mostrar modal ${modalId}:`, e);
        }
    }
    
    // Función para ocultar un modal específico
    function hideModal(modalId) {
        const modalInstance = modalInstances[modalId];
        if (modalInstance) {
            modalInstance.hide();
            console.log(`Modal ${modalId} oculto programáticamente`);
        } else {
            console.warn(`No se encontró instancia para ocultar modal ${modalId}`);
            
            // Intento alternativo
            const modalElement = document.getElementById(modalId);
            if (modalElement) {
                const bsInstance = bootstrap.Modal.getInstance(modalElement);
                if (bsInstance) {
                    bsInstance.hide();
                }
            }
        }
    }
    
    // Limpieza de backdrops
    function cleanupBackdrops() {
        // Solo eliminar backdrops si no hay modales visibles
        if (!document.querySelector('.modal.show')) {
            setTimeout(function() {
                document.querySelectorAll('.modal-backdrop').forEach(function(backdrop) {
                    backdrop.remove();
                });
                document.body.classList.remove('modal-open');
                document.body.style.removeProperty('overflow');
                document.body.style.removeProperty('padding-right');
            }, 150);
        }
    }
    
    // Manejar los botones que abren modales
    function setupModalTriggers() {
        document.querySelectorAll('[data-bs-toggle="modal"]').forEach(function(button) {
            button.addEventListener('click', function(e) {
                const target = this.getAttribute('data-bs-target');
                if (target) {
                    const modalId = target.replace('#', '');
                    showModal(modalId);
                    e.preventDefault(); // Evitar comportamiento por defecto
                }
            });
        });
    }
    
    // Manejar botones de verificación de usuario
    function setupVerifyButtons() {
        document.querySelectorAll('.btn-verify-user').forEach(function(button) {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                
                const userId = this.getAttribute('data-user-id');
                if (!userId) {
                    console.error('ID de usuario no encontrado');
                    return;
                }
                
                verifyUser(userId);
            });
        });
    }
    
    // Función para verificar documentos pendientes de un usuario
    function verifyUser(userId) {
        console.log('Verificando documentos para usuario:', userId);
        
        // Construir URL con CSRF token para la seguridad
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        // Hacer la petición para verificar documentos
        fetch(`/users/${userId}/check-documents`, {
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
                throw new Error('Error en la respuesta del servidor');
            }
            return response.json();
        })
        .then(data => {
            console.log('Respuesta de verificación:', data);
            
            if (data.hasPendingDocuments) {
                // Mostrar modal con documentos pendientes
                showPendingDocumentsModal(userId, data);
            } else {
                // Si no hay documentos pendientes, proceder con la verificación
                submitVerification(userId);
            }
        })
        .catch(error => {
            console.error('Error al verificar documentos:', error);
            // Aquí podrías mostrar un mensaje de error al usuario
            showErrorModal('Error al verificar documentos', 
                'No se pudo verificar el estado de los documentos del usuario. ' + 
                'Por favor, verifica la conexión e intenta nuevamente.');
        });
    }
    
    // Mostrar modal con documentos pendientes
    function showPendingDocumentsModal(userId, data) {
        const modal = document.getElementById('pendingDocumentsModal');
        if (!modal) {
            console.error('Modal de documentos pendientes no encontrado');
            return;
        }
        
        // Llenar el contenido del modal
        const missingList = modal.querySelector('#missingDocumentsList');
        const pendingList = modal.querySelector('#pendingDocumentsList');
        const rejectedList = modal.querySelector('#rejectedDocumentsList');
        
        if (missingList) fillList(missingList, data.missingDocuments);
        if (pendingList) fillList(pendingList, data.pendingDocuments);
        if (rejectedList) fillList(rejectedList, data.rejectedDocuments);
        
        // Configurar botón para forzar verificación
        const forceBtn = modal.querySelector('#forceVerifyBtn');
        if (forceBtn) {
            forceBtn.setAttribute('data-user-id', userId);
            forceBtn.onclick = function() {
                hideModal('pendingDocumentsModal');
                submitVerification(userId, true); // true indica forzar verificación
            };
        }
        
        // Mostrar el modal
        showModal('pendingDocumentsModal');
    }
    
    // Función auxiliar para llenar listas
    function fillList(listElement, items) {
        listElement.innerHTML = '';
        if (items && items.length > 0) {
            listElement.parentElement.style.display = 'block';
            items.forEach(item => {
                const li = document.createElement('li');
                li.textContent = item;
                li.className = 'list-group-item';
                listElement.appendChild(li);
            });
        } else {
            listElement.parentElement.style.display = 'none';
        }
    }
    
    // Enviar solicitud para verificar usuario
    function submitVerification(userId, force = false) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        // Crear la forma para envío
        const form = new FormData();
        form.append('_token', csrfToken);
        if (force) {
            form.append('force', 'true');
        }
        
        // Realizar la petición
        fetch(`/users/${userId}/verify`, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken
            },
            body: form,
            credentials: 'same-origin'
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Error al procesar la verificación');
            }
            return response.json();
        })
        .then(data => {
            console.log('Respuesta de verificación:', data);
            if (data.success) {
                // Actualizar la interfaz
                updateUserVerificationStatus(userId, true);
                showSuccessModal('Usuario Verificado', 'El usuario ha sido verificado correctamente.');
            } else {
                // Mostrar mensaje de error
                showErrorModal('Error de Verificación', data.message || 'No se pudo verificar al usuario.');
            }
        })
        .catch(error => {
            console.error('Error al verificar usuario:', error);
            showErrorModal('Error de Sistema', 'Ocurrió un error al procesar la verificación. Por favor, intenta nuevamente.');
        });
    }
    
    // Actualizar interfaz después de verificación
    function updateUserVerificationStatus(userId, verified) {
        const row = document.querySelector(`tr[data-user-id="${userId}"]`);
        if (!row) return;
        
        const statusBadge = row.querySelector('.verification-status');
        const verifyButton = row.querySelector('.btn-verify-user');
        const unverifyButton = row.querySelector('.btn-unverify-user');
        
        if (statusBadge) {
            statusBadge.textContent = verified ? 'Verificado' : 'No Verificado';
            statusBadge.className = verified ? 'badge bg-success' : 'badge bg-warning verification-status';
        }
        
        if (verifyButton && unverifyButton) {
            verifyButton.style.display = verified ? 'none' : 'inline-block';
            unverifyButton.style.display = verified ? 'inline-block' : 'none';
        }
    }
    
    // Mostrar modal de éxito
    function showSuccessModal(title, message) {
        const modal = document.getElementById('successModal');
        if (!modal) return;
        
        const titleElement = modal.querySelector('.modal-title');
        const messageElement = modal.querySelector('.modal-body p');
        
        if (titleElement) titleElement.textContent = title;
        if (messageElement) messageElement.textContent = message;
        
        showModal('successModal');
    }
    
    // Mostrar modal de error
    function showErrorModal(title, message) {
        const modal = document.getElementById('errorModal');
        if (!modal) return;
        
        const titleElement = modal.querySelector('.modal-title');
        const messageElement = modal.querySelector('.modal-body p');
        
        if (titleElement) titleElement.textContent = title;
        if (messageElement) messageElement.textContent = message;
        
        showModal('errorModal');
    }
    
    // Inicializar todo
    initModals();
    setupModalTriggers();
    setupVerifyButtons();
    
    // Exponer funciones útiles globalmente
    window.userModalUtils = {
        showModal,
        hideModal,
        verifyUser,
        showSuccessModal,
        showErrorModal
    };
});
