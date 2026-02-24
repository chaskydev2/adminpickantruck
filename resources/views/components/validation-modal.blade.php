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

                        {{-- Área de rechazo: inicialmente oculta, se muestra al hacer clic en Rechazar --}}
                        <div id="rejectArea" class="d-none w-100 mt-3">
                            <label for="rejectReason" class="form-label">Motivo del rechazo (opcional pero recomendado)</label>
                            <textarea name="comments" id="rejectReason" class="form-control" rows="3" placeholder="Escribe el motivo del rechazo..."></textarea>
                            <div class="mt-2 d-flex justify-content-end gap-2">
                                <button type="button" class="btn btn-secondary btn-sm" onclick="cancelReject()">Cancelar</button>
                                <button type="button" class="btn btn-danger btn-sm" onclick="confirmReject()">Confirmar Rechazo</button>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center gap-3 mt-3 pt-3 border-top">
                            <div class="d-none gap-2" id="documentActions">
                                <a href="#" id="viewDocument" class="btn btn-outline-info shadow-sm" target="_blank" style="box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.05) !important;">
                                    <i class="fas fa-eye me-2"></i> Ver
                                </a>
                                <a href="#" id="downloadDocument" class="btn btn-outline-primary shadow-sm" download style="box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.05) !important;">
                                    <i class="fas fa-download me-2"></i> Descargar
                                </a>
                            </div>
                            <div class="d-flex gap-2 ms-auto">
                                <button type="button" id="rejectBtn" class="btn btn-outline-secondary shadow-sm" onclick="rejectDocument()" style="box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.05) !important;">
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
        
        // Limpiar overlay de éxito
        const overlay = modal.querySelector('.modal-centered-alert');
        if (overlay) overlay.remove();
        
        // Limpiar id almacenado
        if (modal.dataset && modal.dataset.currentDocumentId) delete modal.dataset.currentDocumentId;
        
        // Resetear el área de rechazo
        const rejectArea = document.getElementById('rejectArea');
        if (rejectArea) {
            rejectArea.classList.add('d-none');
        }
        
        // Limpiar el textarea de rechazo
        const rejectReason = document.getElementById('rejectReason');
        if (rejectReason) {
            rejectReason.value = '';
        }
        
        // Eliminar cualquier mensaje de error
        const rejectError = document.getElementById('rejectError');
        if (rejectError) {
            rejectError.remove();
        }
        
        // Resetear botones
        const rejectBtn = document.getElementById('rejectBtn');
        if (rejectBtn) rejectBtn.disabled = false;
        
        const validateButton = document.getElementById('validateButton');
        if (validateButton) validateButton.disabled = false;
    }
}

// Función para cargar la vista previa del documento
function loadDocumentPreview(documentId) {
    const previewContainer = document.getElementById('documentPreview');
    const downloadLink = document.getElementById('downloadDocument');
    const viewLink = document.getElementById('viewDocument');
    const documentActions = document.getElementById('documentActions');
    
    previewContainer.innerHTML = `
        <div class="text-center py-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Cargando...</span>
            </div>
            <p class="mt-2 text-muted">Cargando documento...</p>
        </div>
    `;
    
    fetch('/documentos-usuarios/' + documentId, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(async response => {
        if (!response.ok) {
            let body = '';
            try { body = await response.text(); } catch (e) { body = ''; }
            throw new Error('Error al cargar el documento. Estado: ' + response.status + ' ' + response.statusText + (body ? ' - ' + body : ''));
        }
        return response.json();
    })
    .then(data => {
        const document = data.document || data;
        let documentUrl = document.document_url || '';

        if (!documentUrl) {
            throw new Error('No se encontró la ruta del documento. Asegúrate de que el archivo exista y que la configuración MAIN_APP_URL sea correcta.');
        }

        // Configurar los botones de ver y descargar
        viewLink.href = documentUrl;
        downloadLink.href = documentUrl;
        documentActions.classList.remove('d-none');
        documentActions.classList.add('d-flex');
        
        // Extraer el nombre del archivo de la URL para el atributo download
        const fileName = documentUrl.split('/').pop().split('?')[0];
        downloadLink.setAttribute('download', fileName);

        const urlParts = documentUrl.split('.');
        const fileExtension = urlParts.length ? urlParts[urlParts.length - 1].split(/[?#]/)[0].toLowerCase() : '';

        if (['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(fileExtension)) {
            previewContainer.innerHTML = '<div class="d-flex justify-content-center align-items-center w-100"><img src="' + documentUrl + '" alt="Documento" class="img-fluid rounded shadow-sm" style="max-width: 90%; height: auto; max-height: 500px; object-fit: contain; display: block; margin: 0 auto;" onerror="this.onerror=null; this.parentElement.innerHTML=\'<div class=\\\'alert alert-danger text-center\\\'>Error al cargar la imagen. Verifique:<br>1. Que el servidor principal (pickandtruckfinal) esté ejecutándose<br>2. Que MAIN_APP_URL esté configurado en el archivo .env<br>3. Que el archivo exista en la ruta especificada</div>\';"></div>';
        } else if (fileExtension === 'pdf') {
            // Usar la ruta proxy del manager para forzar 'inline' y evitar descarga
            const previewUrl = '/documentos-usuarios/' + (document.id || validationModal.dataset.currentDocumentId) + '/preview';
            
            previewContainer.innerHTML = '<div class="w-100" style="height: 500px;"><object data="' + previewUrl + '" type="application/pdf" width="100%" height="100%" class="rounded shadow-sm border"><div class="d-flex align-items-center justify-content-center h-100 bg-light p-4 text-center rounded"><div class="alert alert-warning mb-0"><i class="fas fa-exclamation-triangle me-2"></i>El navegador no puede mostrar este PDF directamente.<br><a href="' + previewUrl + '" target="_blank" class="btn btn-primary btn-sm mt-2">Abrir en nueva pestaña</a></div></div></object></div>';
            
            // Actualizar el botón "Ver" para que también use el proxy
            viewLink.href = previewUrl;
        } else {
            previewContainer.innerHTML = '<div class="alert alert-info text-center mx-auto" style="max-width: 500px;"><i class="fas fa-file me-2"></i> No se puede mostrar una vista previa de este tipo de archivo.<br>Use los botones \"Ver\" o \"Descargar\" para acceder al documento.</div>';
        }
    })
    .catch(error => {
        console.error('Error cargando documento:', error);
        const msg = error && error.message ? error.message : 'Error al cargar el documento.';
        previewContainer.innerHTML = '<div class="alert alert-danger text-center mx-auto" style="max-width: 700px;"><i class="fas fa-exclamation-circle me-2"></i> ' + escapeHtml(msg) + '<br><br><strong>Solución:</strong><br>1. Asegúrate de que el servidor principal (pickandtruckfinal) esté en ejecución<br>2. Verifica que MAIN_APP_URL esté configurado en el archivo .env (ej. MAIN_APP_URL=http://localhost:8000)<br>3. Confirma que el archivo exista en la carpeta public/documents del servidor principal</div>';
        documentActions.classList.remove('d-flex');
        documentActions.classList.add('d-none');
    });
}

function rejectDocument() {
    const rejectArea = document.getElementById('rejectArea');
    const rejectBtn = document.getElementById('rejectBtn');
    const validateButton = document.getElementById('validateButton');

    if (rejectArea) {
        rejectArea.classList.remove('d-none');
        const ta = document.getElementById('rejectReason');
        if (ta) ta.focus();
    }

    if (validateButton) validateButton.disabled = true;
    if (rejectBtn) rejectBtn.disabled = true;
}

function cancelReject() {
    const rejectArea = document.getElementById('rejectArea');
    const validateButton = document.getElementById('validateButton');
    const rejectBtn = document.getElementById('rejectBtn');

    if (rejectArea) {
        rejectArea.classList.add('d-none');
        const ta = document.getElementById('rejectReason');
        if (ta) ta.value = '';
    }

    if (validateButton) validateButton.disabled = false;
    if (rejectBtn) rejectBtn.disabled = false;
}

function confirmReject() {
    const form = document.getElementById('validateDocumentForm');
    if (!form) return;

    const ta = document.getElementById('rejectReason');
    const existingAlert = document.getElementById('rejectError');
    if (existingAlert) existingAlert.remove();

    const value = ta ? (ta.value || '').trim() : '';
    if (value.length < 5) {
        const container = ta ? ta.parentElement : form;
        const err = document.createElement('div');
        err.id = 'rejectError';
        err.className = 'alert alert-danger mt-2';
        err.innerHTML = '<i class="fas fa-exclamation-circle me-2"></i> El motivo debe tener al menos 5 caracteres.';
        if (container) container.insertBefore(err, ta.nextSibling);
        if (ta) ta.focus();
        return;
    }

    const statusInput = form.querySelector('input[name="status"]');
    if (statusInput) statusInput.value = 'rechazado';

    form.dispatchEvent(new Event('submit', { cancelable: true }));
}

function escapeHtml(unsafe) {
    if (unsafe === null || unsafe === undefined) return '';
    return String(unsafe)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}

document.addEventListener('DOMContentLoaded', function() {
    const validationModal = document.getElementById('validationModal');
    
    if (validationModal) {
        document.addEventListener('click', function(e) {
            const button = e.target.closest('.open-validation-modal');
            if (button) {
                e.preventDefault();
                
                const documentId = button.getAttribute('data-document-id');
                const status = button.getAttribute('data-status');
                
                const form = validationModal.querySelector('form');
                form.action = `/documentos-usuarios/${documentId}`;
                validationModal.dataset.currentDocumentId = documentId;
                
                // Resetear el área de rechazo y el formulario
                const rejectArea = document.getElementById('rejectArea');
                if (rejectArea) {
                    rejectArea.classList.add('d-none');
                }
                const rejectReason = document.getElementById('rejectReason');
                if (rejectReason) {
                    rejectReason.value = '';
                }
                const rejectError = document.getElementById('rejectError');
                if (rejectError) {
                    rejectError.remove();
                }
                
                // Resetear el input de status a 'aprobado'
                const statusInput = form.querySelector('input[name="status"]');
                if (statusInput) {
                    statusInput.value = 'aprobado';
                }
                
                // Habilitar todos los botones
                const rejectBtn = document.getElementById('rejectBtn');
                if (rejectBtn) rejectBtn.disabled = false;
                
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
                
                loadDocumentPreview(documentId);
                showModal();
            }
        });
        
        document.getElementById('modalBackdrop').addEventListener('click', function(e) {
            if (e.target === this) {
                hideModal();
            }
        });
        
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                hideModal();
            }
        });
        
        const validateForm = document.getElementById('validateDocumentForm');
        
        if (validateForm) {
            validateForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const form = e.target;
                const formData = new FormData(form);
                const url = form.getAttribute('action');
                
                const submitButton = form.querySelector('button[type="submit"]');
                const originalButtonText = submitButton.innerHTML;
                submitButton.disabled = true;
                submitButton.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Procesando...';
                
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
                        const prevAlerts = validationModal.querySelectorAll('.modal-centered-alert, .alert');
                        prevAlerts.forEach(a => a.remove());

                        const overlay = document.createElement('div');
                        overlay.className = 'modal-centered-alert';
                        overlay.style.cssText = 'position:absolute; inset:0; display:flex; align-items:center; justify-content:center; z-index:10001; pointer-events:none; padding:1rem;';

                        const box = document.createElement('div');
                        box.className = 'alert alert-success d-flex align-items-center';
                        box.style.cssText = 'max-width:640px; width:100%; border-radius:12px; box-shadow:0 8px 24px rgba(16,24,40,0.12); pointer-events:auto;';
                        box.innerHTML = '<div style="font-size:1.35rem; margin-right:0.75rem; color: #0f5132;"><i class="fas fa-check-circle"></i></div><div><strong style="display:block; font-size:1rem;">' + (data.message || 'Documento actualizado exitosamente.') + '</strong><small class="d-block text-muted">Los cambios se guardaron correctamente.</small></div>';

                        overlay.appendChild(box);

                        const modalContent = validationModal.querySelector('.modal-content');
                        if (modalContent) modalContent.appendChild(overlay);

                        const validateButton = document.getElementById('validateButton');
                        if (validateButton) {
                            validateButton.disabled = true;
                            validateButton.innerHTML = '<i class="fas fa-check-circle me-2"></i> Documento Aprobado';
                            validateButton.classList.remove('btn-success');
                            validateButton.classList.add('btn-outline-success');
                        }

                        try {
                            const currentId = (validationModal && validationModal.dataset && validationModal.dataset.currentDocumentId) ? validationModal.dataset.currentDocumentId : (data.document.id || data.document);

                            let tr = document.querySelector('tr[data-user-document-id="' + currentId + '"]');

                            if (!tr) {
                                const rows = Array.from(document.querySelectorAll('tr[data-user-document-id]'));
                                tr = rows.find(r => String(r.getAttribute('data-user-document-id')) === String(currentId));
                            }

                            if (!tr) {
                                const btn = document.querySelector('.open-validation-modal[data-document-id="' + currentId + '"]');
                                if (btn) tr = btn.closest('tr');
                            }

                            if (!tr) {
                                console.warn('No se encontró la fila para data-user-document-id:', currentId);
                            } else {
                                const cells = tr.querySelectorAll('td');
                                const numCols = cells.length;
                                
                                const doc = data.document || {};

                                const statusLabel = doc.status_label || (doc.status ? (doc.status.charAt(0).toUpperCase() + doc.status.slice(1)) : 'Pendiente');
                                const statusClass = doc.status_class || ((doc.status === 'aprobado') ? 'bg-success' : ((doc.status === 'rechazado') ? 'bg-danger' : 'bg-warning'));
                                const createdAt = doc.created_at_formatted || doc.created_at || '';
                                const docId = doc.id || currentId;

                                let newRowHtml = '';

                                if (numCols === 5) {
                                    const userName = doc.user_name || (doc.user && (doc.user.name || 'N/A')) || 'N/A';
                                    const userEmail = doc.user_email || (doc.user && (doc.user.email || 'N/A')) || 'N/A';
                                    
                                    newRowHtml = `
                                        <td>${escapeHtml(userName)}</td>
                                        <td>${escapeHtml(userEmail)}</td>
                                        <td><span class="badge ${escapeHtml(statusClass)}">${escapeHtml(statusLabel)}</span></td>
                                        <td>${escapeHtml(createdAt)}</td>
                                        <td class="text-center">
                                            <button type="button" 
                                                    class="btn btn-sm ${doc.status === 'aprobado' ? 'btn-outline-success' : 'btn-outline-primary'} open-validation-modal"
                                                    data-document-id="${escapeHtml(docId)}"
                                                    data-status="${escapeHtml(doc.status || '')}">
                                                <i class="far ${doc.status === 'aprobado' ? 'fa-check-circle' : 'fa-eye'} me-1"></i>
                                                ${doc.status === 'aprobado' ? 'Validado' : 'Ver'}
                                            </button>
                                        </td>
                                    `;
                                } else if (numCols === 4) {
                                    const docType = doc.required_document_name || (doc.requiredDocument && doc.requiredDocument.name) || 'Documento';
                                    
                                    newRowHtml = `
                                        <td>${escapeHtml(docType)}</td>
                                        <td><span class="badge ${escapeHtml(statusClass)}">${escapeHtml(statusLabel)}</span></td>
                                        <td>${escapeHtml(createdAt)}</td>
                                        <td>
                                            <button type="button" 
                                                    class="btn btn-sm btn-outline-primary open-validation-modal" 
                                                    data-document-id="${escapeHtml(docId)}"
                                                    data-status="${escapeHtml(doc.status || '')}"
                                                    data-bs-toggle="tooltip" 
                                                    title="Ver documento">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </td>
                                    `;
                                } else {
                                    console.warn('Estructura de tabla no reconocida (columnas: ' + numCols + ')');
                                    const statusIdx = numCols === 3 ? 1 : (numCols > 3 ? 2 : 1);
                                    const dateIdx = statusIdx + 1;
                                    
                                    if (cells[statusIdx]) {
                                        cells[statusIdx].innerHTML = '<span class="badge ' + escapeHtml(statusClass) + '">' + escapeHtml(statusLabel) + '</span>';
                                    }
                                    if (cells[dateIdx] && createdAt) {
                                        cells[dateIdx].textContent = createdAt;
                                    }
                                    
                                    const actionBtn = tr.querySelector('.open-validation-modal');
                                    if (actionBtn) {
                                        actionBtn.setAttribute('data-status', doc.status || '');
                                    }
                                }

                                if (newRowHtml) {
                                    tr.innerHTML = newRowHtml;
                                }

                                tr.style.transition = 'background-color 0.3s ease';
                                tr.style.backgroundColor = 'rgba(16, 185, 129, 0.08)';
                                setTimeout(() => { tr.style.backgroundColor = ''; }, 1200);

                                // LÓGICA MEJORADA: Verificar estado de documentos
                                try {
                                    // 1. Encontrar la tabla de documentos
                                    const documentsTable = tr.closest('table');
                                    
                                    if (documentsTable) {
                                        // 2. Verificar TODOS los botones 'action' en la tabla
                                        // Usamos data-status que es más confiable que las clases CSS
                                        const actionButtons = documentsTable.querySelectorAll('.open-validation-modal');
                                        let allApproved = true;
                                        
                                        if (actionButtons.length > 0) {
                                            actionButtons.forEach(btn => {
                                                const status = btn.getAttribute('data-status');
                                                // Si alguno no está aprobado, marcamos bandera falsa
                                                if (status !== 'aprobado') {
                                                    allApproved = false;
                                                }
                                            });
                                        } else {
                                            // Fallback por si no encuentra botones (ej: estructura diferente)
                                            allApproved = false;
                                        }

                                        // 3. Buscar el botón "Verificar Usuario"
                                        // Buscamos en el contenedor más cercano que agrupe tabla y botón
                                        let container = documentsTable.closest('.card-body');
                                        if (!container) container = documentsTable.closest('.document-list'); // Intento alternativo
                                        
                                        if (container) {
                                            const verifyButton = container.querySelector('.verify-user');
                                            
                                            if (verifyButton) {
                                                // 4. Actualizar estado del botón
                                                if (allApproved) {
                                                    verifyButton.disabled = false;
                                                    verifyButton.classList.remove('btn-secondary');
                                                    verifyButton.classList.add('btn-success');
                                                    verifyButton.innerHTML = '<i class="fas fa-user-check me-1"></i> Verificar Usuario'; // Restaurar icono
                                                    verifyButton.setAttribute('title', 'Verificar este usuario');
                                                    verifyButton.removeAttribute('disabled'); // Asegurar remoción
                                                } else {
                                                    verifyButton.disabled = true;
                                                    verifyButton.classList.remove('btn-success');
                                                    verifyButton.classList.add('btn-secondary');
                                                    verifyButton.setAttribute('title', 'Todos los documentos deben estar aprobados');
                                                    verifyButton.setAttribute('disabled', 'disabled');
                                                }
                                                
                                                // Actualizar tooltip de Bootstrap si existe
                                                if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
                                                    try {
                                                        const tooltip = bootstrap.Tooltip.getInstance(verifyButton) || new bootstrap.Tooltip(verifyButton);
                                                        tooltip.setAttribute('data-bs-original-title', allApproved ? 'Verificar este usuario' : 'Todos los documentos deben estar aprobados');
                                                    } catch(e) {}
                                                }
                                            }
                                        }
                                    }
                                } catch (err) {
                                    console.error('Error al actualizar botón de verificación:', err);
                                }

                            }
                        } catch (err) {
                            console.warn('No se pudo actualizar la fila:', err);
                        }

                        setTimeout(() => {
                            hideModal();
                        }, 1200);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    submitButton.disabled = false;
                    submitButton.innerHTML = originalButtonText;
                    
                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'alert alert-danger mt-3 mb-0';
                    
                    let errorMessage = 'Error al procesar la solicitud. Intente nuevamente.';
                    
                    if (error.response && error.response.message) {
                        errorMessage = error.response.message;
                    } else if (error.message) {
                        errorMessage = error.message;
                    }
                    
                    errorDiv.innerHTML = `<i class="fas fa-exclamation-circle me-2"></i> ${errorMessage}`;
                    
                    const existingAlerts = form.parentNode.querySelectorAll('.alert');
                    existingAlerts.forEach(alert => alert.remove());
                    
                    form.parentNode.insertBefore(errorDiv, form.nextSibling);
                    
                    errorDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
                });
            });
        }
    }
});
</script>
@endpush
