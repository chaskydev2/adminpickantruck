<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Administradores del Sistema') }}
            </h2>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createAdminModal">
                <i class="fas fa-plus-circle me-2"></i>Nuevo Administrador
            </button>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Email</th>
                                    <th>Rol</th>
                                    <th>Fecha de Registro</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($administrators as $admin)
                                <tr>
                                    <td>{{ $admin->name }}</td>
                                    <td>{{ $admin->email }}</td>
                                    <td>
                                        <span class="badge {{ $admin->role === 'admin' ? 'bg-danger' : 'bg-info' }}">
                                            {{ $admin->role === 'admin' ? 'Administrador' : 'Editor' }}
                                        </span>
                                    </td>
                                    <td>{{ $admin->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editAdminModal{{ $admin->id }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteAdminModal{{ $admin->id }}">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Modal de Edición -->
                                <div class="modal fade" id="editAdminModal{{ $admin->id }}" tabindex="-1" aria-labelledby="editAdminModalLabel{{ $admin->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('administrators.update', $admin) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editAdminModalLabel{{ $admin->id }}">Editar Administrador</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label for="name" class="form-label">Nombre</label>
                                                        <input type="text" class="form-control" id="name" name="name" value="{{ $admin->name }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="email" class="form-label">Email</label>
                                                        <input type="email" class="form-control" id="email" name="email" value="{{ $admin->email }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="role" class="form-label">Rol</label>
                                                        <select class="form-select" id="role" name="role" required>
                                                            <option value="admin" {{ $admin->role === 'admin' ? 'selected' : '' }}>Administrador</option>
                                                            <option value="editor" {{ $admin->role === 'editor' ? 'selected' : '' }}>Editor</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="password" class="form-label">Contraseña (dejar en blanco para mantener la actual)</label>
                                                        <input type="password" class="form-control" id="password" name="password">
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal de Eliminación -->
                                <div class="modal fade" id="deleteAdminModal{{ $admin->id }}" tabindex="-1" aria-labelledby="deleteAdminModalLabel{{ $admin->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('administrators.destroy', $admin) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="deleteAdminModalLabel{{ $admin->id }}">Confirmar Eliminación</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>¿Estás seguro de que deseas eliminar al administrador <strong>{{ $admin->name }}</strong>?</p>
                                                    <p class="text-danger">Esta acción no se puede deshacer.</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                    <button type="submit" class="btn btn-danger">Eliminar</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Creación -->
    <div class="modal fade" id="createAdminModal" tabindex="-1" aria-labelledby="createAdminModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('administrators.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="createAdminModalLabel">Nuevo Administrador</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="role" class="form-label">Rol</label>
                            <select class="form-select" id="role" name="role" required>
                                <option value="admin">Administrador</option>
                                <option value="editor">Editor</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Contraseña</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Crear Administrador</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Código para inicializar manualmente los modales de Bootstrap
            var modalTriggerButtons = document.querySelectorAll('[data-bs-toggle="modal"]');
            modalTriggerButtons.forEach(function(button) {
                button.addEventListener('click', function() {
                    var target = this.getAttribute('data-bs-target');
                    var modal = new bootstrap.Modal(document.querySelector(target));
                    modal.show();
                });
            });

            // Manejar cierre del modal de forma manual para asegurar que se elimina el backdrop
            document.querySelectorAll('.modal .btn-close, .modal .btn-secondary').forEach(function(button) {
                button.addEventListener('click', function() {
                    // Encontrar el modal asociado
                    const modalElement = this.closest('.modal');
                    if (modalElement) {
                        // Cerrar el modal usando Bootstrap
                        const modalInstance = bootstrap.Modal.getInstance(modalElement);
                        if (modalInstance) {
                            modalInstance.hide();
                        }
                        
                        // Limpieza manual adicional después de un breve retraso
                        setTimeout(function() {
                            // Eliminar cualquier backdrop residual
                            const backdrops = document.querySelectorAll('.modal-backdrop');
                            backdrops.forEach(function(backdrop) {
                                backdrop.remove();
                            });
                            
                            // Restablecer estilos del body
                            document.body.classList.remove('modal-open');
                            document.body.style.removeProperty('overflow');
                            document.body.style.removeProperty('padding-right');
                        }, 300);
                    }
                });
            });
            
            // También manejar el evento de cierre del modal a través de ESC o clic fuera
            document.querySelectorAll('.modal').forEach(function(modal) {
                modal.addEventListener('hidden.bs.modal', function() {
                    // Limpieza manual
                    setTimeout(function() {
                        const backdrops = document.querySelectorAll('.modal-backdrop');
                        backdrops.forEach(function(backdrop) {
                            backdrop.remove();
                        });
                        document.body.classList.remove('modal-open');
                        document.body.style.removeProperty('overflow');
                        document.body.style.removeProperty('padding-right');
                    }, 300);
                });
            });

            // Registrar eventos de modal para seguimiento
            document.querySelectorAll('.modal').forEach(function(modal) {
                // Cuando se muestra el modal
                modal.addEventListener('show.bs.modal', function() {
                    console.log('Modal abierto:', this.id);
                });
                
                // Cuando se ha mostrado completamente
                modal.addEventListener('shown.bs.modal', function() {
                    // Verificar que el backdrop existe
                    if (!document.querySelector('.modal-backdrop')) {
                        console.warn('Backdrop missing, creating one');
                        var backdrop = document.createElement('div');
                        backdrop.classList.add('modal-backdrop', 'fade', 'show');
                        document.body.appendChild(backdrop);
                    }
                });
                
                // Cuando el modal se está ocultando
                modal.addEventListener('hide.bs.modal', function() {
                    console.log('Modal cerrándose:', this.id);
                });
                
                // Cuando el modal se ha ocultado completamente
                modal.addEventListener('hidden.bs.modal', function() {
                    console.log('Modal cerrado:', this.id);
                    
                    // Si no hay más modales abiertos, limpiar backdrops y restaurar el body
                    if (!document.querySelector('.modal.show')) {
                        setTimeout(function() {
                            var backdrops = document.querySelectorAll('.modal-backdrop');
                            if (backdrops.length > 0) {
                                console.log('Limpiando backdrops residuales:', backdrops.length);
                                backdrops.forEach(function(backdrop) {
                                    backdrop.classList.remove('show');
                                    setTimeout(function() {
                                        backdrop.remove();
                                    }, 150);
                                });
                            }
                            
                            // Restaurar el body solo si no hay modales abiertos
                            if (!document.querySelector('.modal.show')) {
                                document.body.classList.remove('modal-open');
                                document.body.style.removeProperty('overflow');
                                document.body.style.removeProperty('padding-right');
                            }
                        }, 150);
                    }
                });
            });
        });
    </script>
    @endpush
</x-app-layout>
