<style>
    .sidebar-nav {
        background: linear-gradient(180deg, #142e54 0%, #0c1f3d 100%);
        height: 100%;
        padding: 1.5rem 0.75rem;
        box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
    }
    
    .sidebar-nav .nav-item {
        margin-bottom: 0.5rem;
    }
    
    .sidebar-nav .nav-link {
        color: rgba(255, 255, 255, 0.85) !important;
        padding: 0.75rem 1rem;
        border-radius: 6px;
        font-size: 0.95rem;
        font-weight: 500;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        position: relative;
        overflow: hidden;
    }
    
    .sidebar-nav .nav-link i {
        width: 24px;
        text-align: center;
        margin-right: 12px;
        font-size: 1.1rem;
        color: rgba(255, 255, 255, 0.7);
        transition: all 0.3s ease;
    }
    
    .sidebar-nav .nav-link:hover {
        background-color: rgba(255, 255, 255, 0.1) !important;
        color: #fff !important;
        transform: translateX(5px);
    }
    
    .sidebar-nav .nav-link:hover i {
        color: #fff;
        transform: scale(1.1);
    }
    
    .sidebar-nav .nav-link.active {
        background: linear-gradient(90deg, rgba(0, 123, 255, 0.2) 0%, rgba(0, 123, 255, 0.1) 100%) !important;
        color: #fff !important;
        font-weight: 600;
        border-left: 3px solid #007bff;
    }
    
    .sidebar-nav .nav-link.active i {
        color: #fff;
    }
    
    .sidebar-nav .nav-link::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        height: 100%;
        width: 3px;
        background-color: #007bff;
        transform: scaleY(0);
        transition: transform 0.2s ease;
    }
    
    .sidebar-nav .nav-link:hover::before {
        transform: scaleY(1);
    }
    
    .sidebar-nav .nav-item:last-child {
        margin-top: 1.5rem;
        position: relative;
    }
    
    .sidebar-nav .nav-item:last-child::before {
        content: '';
        position: absolute;
        top: -0.75rem;
        left: 1rem;
        right: 1rem;
        height: 1px;
        background-color: rgba(255, 255, 255, 0.1);
    }
</style>

<div class="sidebar-nav">
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                <i class="fas fa-chart-pie me-2"></i> Resumen
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('usuarios*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                <i class="fas fa-user-tie me-2"></i> Usuarios
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('documents*') ? 'active' : '' }}" href="{{ route('documents.index') }}">
                <i class="fas fa-file-contract me-2"></i> Documentos Requeridos
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('cargas*') ? 'active' : '' }}" href="{{ route('cargas.index') }}">
                <i class="fas fa-boxes me-2"></i> Publicaciones de Carga
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('rutas*') ? 'active' : '' }}" href="{{ route('rutas.index') }}">
                <i class="fas fa-route me-2"></i> Publicaciones de Ruta
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('bids*') ? 'active' : '' }}" href="{{ route('bids.index') }}">
                <i class="fas fa-hand-holding-usd me-2"></i> Pujas
            </a>
        </li>
    </ul>
</div>