<style>
    .custom-header {
        background-color:rgb(5, 29, 52) !important;
        min-height: 56px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .navbar-brand {
        padding: 5px 0;
    }
    
    .navbar-brand img {
        height: 40px;
        width: auto;
        transition: all 0.3s ease;
    }
    
    .user-avatar {
        width: 36px !important;
        height: 36px !important;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 1rem;
    }
    
    .user-name {
        font-size: 0.9rem;
        font-weight: 500;
    }
</style>

<nav class="navbar navbar-expand-lg navbar-dark custom-header">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center" href="{{ route('dashboard') }}">
            <img src="{{ asset('images/pickntruck.png') }}" alt="PicknTruck" class="me-2">
        </a>

        <div class="ms-auto d-flex align-items-center">
            <span class="text-white me-3 d-none d-md-inline user-name">{{ Auth::user()->name }}</span>
            <div class="dropdown">
                <button class="btn btn-light rounded-circle user-avatar"
                        data-bs-toggle="dropdown" aria-expanded="false">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="{{ route('profile.show') }}"><i class="fas fa-user me-2"></i>Mi Perfil</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item">
                                <i class="fas fa-sign-out-alt me-2"></i>Cerrar Sesión
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>