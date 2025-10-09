@props([
    'documentId' => null,
    'status' => 'pendiente',
])

<!-- Modal personalizado sin backdrop de Bootstrap -->
<div class="position-fixed top-0 start-0 w-100 h-100" id="validationModal" style="display: none; z-index: 9999;">
    <!-- Fondo oscuro personalizado -->
    <div class="position-fixed top-0 start-0 w-100 h-100 bg-dark opacity-50" id="modalBackdrop" style="z-index: 9998;"></div>
    
    <!-- Contenido del modal -->
    <div class="position-absolute top-50 start-50 translate-middle" style="z-index: 10000; width: 90%; max-width: 800px;">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 12px; overflow: hidden;">
            <div class="modal-header bg-white border-bottom" style="box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.05);">
                <h5 class="modal-title fw-bold text-dark">Validar Documento</h5>
                <button type="button" class="btn-close" onclick="hideModal()" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body text-center p-0" style="max-height: 70vh; overflow-y: auto;">
                <div class="mb-4">
                    <div class="bg-light p-3 rounded-3 border shadow-sm" style="background-color: #f8fafc !important;">
                        <div class="bg-white p-4 rounded-3 shadow-sm mb-3 d-flex justify-content-center align-items-center" id="documentPreview" style="box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.05) !important; min-height: 400px;">
                            <!-- El contenido se cargará dinámicamente con JavaScript -->
                            <div class="text-center py-4">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Cargando...</span>
                                </div>
                                <p class="mt-2 text-muted">Cargando documento...</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-center gap-2 p-3">
                    <form id="validateDocumentForm" method="POST" action="" class="w-100">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="aprobado">
                        <div class="d-flex justify-content-between align-items-center gap-3 mt-3 pt-3 border-top">
                            <a href="#" id="downloadDocument" class="btn btn-outline-primary shadow-sm" target="_blank" style="display: none; box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.05) !important;">
                                <i class="fas fa-download me-2"></i> Descargar
                            </a>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-outline-secondary shadow-sm" onclick="rejectDocument()" style="box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.05) !important;">
                                    <i class="fas fa-times me-2"></i> Rechazar
                                </button>
                                <button type="submit" class="btn btn-success shadow-sm" id="validateButton" style="box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.05) !important;">
                                    <i class="fas fa-check me-2"></i> Aprobar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Cerrar el modal al hacer clic en el backdrop
document.addEventListener('click', function(event) {
    const modal = document.getElementById('validationModal');
    const modalContent = document.querySelector('#validationModal .modal-content');
    const backdrop = document.getElementById('validationModalBackdrop');
    
    // Solo cerrar si se hace clic directamente en el backdrop
    if (backdrop && event.target === backdrop) {
        // Ocultar el backdrop
        backdrop.style.display = 'none';
        backdrop.style.pointerEvents = 'none';
        
        // Ocultar el modal
        const modalInstance = bootstrap.Modal.getInstance(modal);
        if (modalInstance) {
            modalInstance.hide();
        }
    }
});

// Función para mostrar el modal
function showModal() {
    const modal = document.getElementById('validationModal');
    if (modal) {
        modal.style.display = 'block';
        document.body.style.overflow = 'hidden';
    }
}

// Función para ocultar el modal
function hideModal() {
    const modal = document.getElementById('validationModal');
    if (modal) {
        modal.style.display = 'none';
        document.body.style.overflow = '';
    }
}

// Función para cargar la vista previa del documento
function loadDocumentPreview(documentId) {
    const previewContainer = document.getElementById('documentPreview');
    const downloadLink = document.getElementById('downloadDocument');
    
    // Mostrar indicador de carga
    previewContainer.innerHTML = `
        <div class="text-center py-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Cargando...</span>
            </div>
            <p class="mt-2 text-muted">Cargando documento...</p>
        </div>
    `;
    
    // Hacer petición para obtener los datos del documento
    fetch('/documentos-usuarios/' + documentId, {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Error al cargar el documento');
        }
        return response.json();
    })
    .then(data => {
        // El documento viene del servidor con su información
        const document = data.document || data;
        
        // Usar el atributo document_url que ya viene procesado del modelo
        let documentUrl = document.document_url || '';
        
        if (!documentUrl) {
            throw new Error('No se encontró la ruta del documento');
        }
        
        // Actualizar el enlace de descarga
        downloadLink.href = documentUrl;
        downloadLink.style.display = 'inline-block';
        
        // Determinar el tipo de archivo
        const fileExtension = (document.file_path || '').split('.').pop().toLowerCase();
        
        if (['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(fileExtension)) {
            // Es una imagen - centrada
            previewContainer.innerHTML = '<div class="d-flex justify-content-center align-items-center w-100"><img src="' + documentUrl + '" alt="Documento" class="img-fluid rounded shadow-sm" style="max-width: 90%; height: auto; max-height: 500px; object-fit: contain; display: block; margin: 0 auto;" onerror="this.onerror=null; this.parentElement.innerHTML=\'<div class=\\\'alert alert-danger text-center\\\'>Error al cargar la imagen. Asegúrate de que el servidor principal (pickandtruckfinal) esté ejecutándose en http://localhost:8000</div>\';"></div>';
        } else if (fileExtension === 'pdf') {
            // Es un PDF - ocupa todo el ancho
            previewContainer.innerHTML = '<div class="w-100"><iframe src="' + documentUrl + '" style="width: 100%; height: 500px; border: none; border-radius: 8px; display: block;"></iframe></div>';
        } else {
            // Otro tipo de archivo - centrado
            previewContainer.innerHTML = '<div class="alert alert-info text-center mx-auto" style="max-width: 500px;"><i class="fas fa-file me-2"></i> No se puede mostrar una vista previa de este tipo de archivo. <br><a href="' + documentUrl + '" target="_blank" class="btn btn-sm btn-primary mt-2"><i class="fas fa-download me-1"></i> Descargar archivo</a></div>';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        previewContainer.innerHTML = '<div class="alert alert-danger text-center mx-auto" style="max-width: 600px;"><i class="fas fa-exclamation-circle me-2"></i> Error al cargar el documento: ' + error.message + '<br><br><strong>Nota:</strong> Los documentos están almacenados en el servidor principal de pickandtruckfinal (http://localhost:8000). Asegúrate de que ese servidor esté en funcionamiento.</div>';
        downloadLink.style.display = 'none';
    });
}

document.addEventListener('DOMContentLoaded', function() {
    const validationModal = document.getElementById('validationModal');
    const closeButton = document.getElementById('closeModal');
    
    if (validationModal) {
        // Manejar la apertura del modal
        document.addEventListener('click', function(e) {
            const button = e.target.closest('.open-validation-modal');
            if (button) {
                e.preventDefault();
                
                const documentId = button.getAttribute('data-document-id');
                const status = button.getAttribute('data-status');
                
                // Actualizar el formulario con los datos del documento
                const form = validationModal.querySelector('form');
                form.action = `/documentos-usuarios/${documentId}`;
                
                // Actualizar el estado del botón
                const validateButton = validationModal.querySelector('#validateButton');
                if (status === 'aprobado') {
                    validateButton.disabled = true;
                    validateButton.innerHTML = '<i class="fas fa-check-circle me-1"></i> Documento Aprobado';
                    validateButton.classList.remove('btn-success');
                    validateButton.classList.add('btn-outline-success');
                } else {
                    validateButton.disabled = false;
                    validateButton.innerHTML = '<i class="fas fa-check me-1"></i> Validar Documento';
                    validateButton.classList.remove('btn-outline-success');
                    validateButton.classList.add('btn-success');
                }
                
                // Cargar el documento
                loadDocumentPreview(documentId);
                
                // Mostrar el modal
                showModal();
            }
        });
        
        // Cerrar al hacer clic fuera del modal (en el backdrop)
        document.getElementById('modalBackdrop').addEventListener('click', function(e) {
            if (e.target === this) {
                hideModal();
            }
        });
        
        // Cerrar con la tecla ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                hideModal();
            }
        });
        
        // Manejar el envío del formulario
        const validateForm = document.getElementById('validateDocumentForm');
        
        if (validateForm) {
            validateForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const form = e.target;
                const formData = new FormData(form);
                const url = form.getAttribute('action');
                
                // Mostrar indicador de carga
                const submitButton = form.querySelector('button[type="submit"]');
                const originalButtonText = submitButton.innerHTML;
                submitButton.disabled = true;
                submitButton.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Procesando...';
                
                fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-HTTP-Method-Override': 'PUT'
                    },
                    body: formData
                })
                .then(async response => {
                    const data = await response.json().catch(() => ({}));
                    
                    if (!response.ok) {
                        const error = new Error(data.message || 'Error en la respuesta del servidor');
                        error.response = data;
                        throw error;
                    }
                    
                    return data;
                })
                .then(data => {
                    if (data.success) {
                        // Mostrar mensaje de éxito
                        const successMessage = document.createElement('div');
                        successMessage.className = 'alert alert-success mt-3 mb-0';
                        successMessage.innerHTML = '<i class="fas fa-check-circle me-2"></i> ' + data.message;
                        form.parentNode.insertBefore(successMessage, form.nextSibling);
                        
                        // Deshabilitar el botón de validar
                        const validateButton = document.getElementById('validateButton');
                        validateButton.disabled = true;
                        validateButton.innerHTML = '<i class="fas fa-check-circle me-2"></i> Documento Aprobado';
                        validateButton.classList.remove('btn-success');
                        validateButton.classList.add('btn-outline-success');
                        
                        // Cerrar el modal después de 1.5 segundos
                        setTimeout(() => {
                            hideModal();
                            // Recargar la página para ver los cambios
                            window.location.reload();
                        }, 1500);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    // Restaurar el botón
                    submitButton.disabled = false;
                    submitButton.innerHTML = originalButtonText;
                    
                    // Mostrar mensaje de error con detalles
                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'alert alert-danger mt-3 mb-0';
                    
                    let errorMessage = 'Error al procesar la solicitud. Por favor, intente nuevamente.';
                    
                    if (error.response && error.response.message) {
                        errorMessage = error.response.message;
                    } else if (error.message) {
                        errorMessage = error.message;
                    }
                    
                    errorDiv.innerHTML = `<i class="fas fa-exclamation-circle me-2"></i> ${errorMessage}`;
                    
                    // Eliminar mensajes de error anteriores
                    const existingAlerts = form.parentNode.querySelectorAll('.alert');
                    existingAlerts.forEach(alert => alert.remove());
                    
                    form.parentNode.insertBefore(errorDiv, form.nextSibling);
                    
                    // Desplazarse al mensaje de error
                    errorDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
                });
            });
        }
    }
});
</script>
@endpush
