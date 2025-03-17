<nav x-data="{ open: false }" class="bg-navigation border-b border-gray-700">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center">
                        <div class="logo-container">
                            <div class="logo-icon">
                                <i class="fas fa-truck text-primary"></i>
                            </div>
                            <div class="logo-text d-none d-sm-flex">
                                <span>Pick<span class="text-primary">n</span>truck</span>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <x-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')" class="text-white-nav montserrat-menu">
                        <i class="fas fa-users me-2"></i>{{ __('Usuarios') }}
                    </x-nav-link>

                    <x-nav-link :href="route('documents.index')" :active="request()->routeIs('documents.*')" class="text-white-nav montserrat-menu">
                        <i class="fas fa-file-alt me-2"></i>{{ __('Documentos Requeridos') }}
                    </x-nav-link>

                    @if(auth()->user()->isAdmin())
                    <x-nav-link :href="route('administrators.index')" :active="request()->routeIs('administrators.*')" class="text-white-nav montserrat-menu">
                        <i class="fas fa-user-shield me-2"></i>{{ __('Administradores') }}
                    </x-nav-link>
                    @endif

                    <!-- Menú desplegable de Ofertas -->
                    <div class="hidden sm:flex sm:items-center">
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center px-3 py-2 text-sm leading-4 font-medium rounded-md text-white bg-transparent hover:text-gray-300 focus:outline-none transition ease-in-out duration-150 montserrat-menu">
                                    <i class="fas fa-hand-holding-usd me-2"></i>
                                    <div>{{ __('Ofertas') }}</div>
                                    <div class="ml-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 011.414 1.414l-4 4a1 1 01-1.414 0l-4-4a1 1 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <x-dropdown-link :href="route('ofertas.cargas')" class="montserrat-menu">
                                    <i class="fas fa-box me-2"></i>{{ __('Ofertas de Carga') }}
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('ofertas.rutas')" class="montserrat-menu">
                                    <i class="fas fa-route me-2"></i>{{ __('Ofertas de Ruta') }}
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('ofertas.pujas')" class="montserrat-menu">
                                    <i class="fas fa-gavel me-2"></i>{{ __('Pujas') }}
                                </x-dropdown-link>
                            </x-slot>
                        </x-dropdown>
                    </div>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div id="profile-dropdown" class="hidden sm:flex sm:items-center sm:ml-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-transparent hover:text-gray-300 focus:outline-none transition ease-in-out duration-150 montserrat-menu">
                            <div>{{ Auth::user()->name }}</div>
                            <div class="ml-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 011.414 1.414l-4 4a1 1 01-1.414 0l-4-4a1 1 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')" class="montserrat-menu">
                            <i class="fas fa-user-cog me-2"></i>{{ __('Perfil') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" class="montserrat-menu"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                <i class="fas fa-sign-out-alt me-2"></i>{{ __('Cerrar Sesión') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-white hover:text-gray-300 hover:bg-gray-700 focus:outline-none focus:bg-gray-700 focus:text-white transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')" class="text-white-nav montserrat-menu">
                <i class="fas fa-users me-2"></i>{{ __('Usuarios') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('documents.index')" :active="request()->routeIs('documents.*')" class="text-white-nav montserrat-menu">
                <i class="fas fa-file-alt me-2"></i>{{ __('Documentos Requeridos') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('administrators.index')" :active="request()->routeIs('administrators.*')" class="text-white-nav montserrat-menu">
                <i class="fas fa-user-shield me-2"></i>{{ __('Administradores') }}
            </x-responsive-nav-link>
            
            <!-- Links de ofertas responsivos -->
            <x-responsive-nav-link :href="route('ofertas.cargas')" :active="request()->routeIs('ofertas.cargas')" class="text-white-nav montserrat-menu">
                <i class="fas fa-box me-2"></i>{{ __('Publicaciones de Carga') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('ofertas.rutas')" :active="request()->routeIs('ofertas.rutas')" class="text-white-nav montserrat-menu">
                <i class="fas fa-route me-2"></i>{{ __('Publicaciones de Ruta') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('ofertas.pujas')" :active="request()->routeIs('ofertas.pujas')" class="text-white-nav montserrat-menu">
                <i class="fas fa-gavel me-2"></i>{{ __('Ofertas') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-700">
            <div class="px-4">
                <div class="font-medium text-base text-white montserrat-menu">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-300 montserrat-menu">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')" class="text-white-nav montserrat-menu">
                    <i class="fas fa-user-cog me-2"></i>{{ __('Perfil') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')" class="text-white-nav montserrat-menu"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        <i class="fas fa-sign-out-alt me-2"></i>{{ __('Cerrar Sesión') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>

@push('styles')
<style>
    .bg-navigation {
        background-color: #1A202C;
    }
    .text-white-nav {
        color: white !important;
    }
    .text-white-nav:hover {
        color: #d1d5db !important;
    }
    .border-gray-700 {
        border-color: #374151;
    }
    .nav-link i, .dropdown-link i {
        width: 20px;
        text-align: center;
    }
    .nav-link:hover, .dropdown-link:hover {
        color: #d1d5db !important;
    }
    .dropdown-link {
        display: flex;
        align-items: center;
    }
    .nav-link.active {
        border-bottom-color: #4f46e5 !important;
        color: white !important;
    }
    /* Sobreescribir estilos para el dropdown menu */
    .dropdown-content {
        background-color: #1A202C;
        border-color: #374151;
    }
    .dropdown-link {
        color: white !important;
    }
    .dropdown-link:hover {
        background-color: #2d3748;
    }
    
    /* Corrección para el texto en el dropdown trigger */
    button.inline-flex {
        color: white !important;
    }
    
    /* Asegurar que el ícono del dropdown sea visible */
    button.inline-flex svg {
        color: white !important;
    }
    
    /* Asegurar que el nombre de usuario siempre sea blanco */
    .hidden.sm\:flex.sm\:items-center button div {
        color: white !important;
    }
    
    /* Estilo Montserrat para elementos del menú */
    .montserrat-menu {
        font-family: 'Montserrat', sans-serif !important;
        font-weight: 500 !important;
    }
    
    /* Ajustar tamaño y espaciado para mejor legibilidad */
    .nav-link {
        letter-spacing: 0.01em !important;
    }
    
    .dropdown-link {
        letter-spacing: 0.01em !important;
    }

    /* Quitar subrayado de enlaces del menú */
    .nav-link, .dropdown-link, .responsive-nav-link {
        text-decoration: none !important;
    }
    
    /* Quitar subrayado también al pasar el cursor */
    .nav-link:hover, .dropdown-link:hover, .responsive-nav-link:hover {
        text-decoration: none !important;
    }
    
    /* Eliminar subrayado en enlaces activos */
    .nav-link.active {
        border-bottom-color: #4f46e5 !important;
        color: white !important;
        text-decoration: none !important;
    }
    
    /* Asegurar que no haya subrayado en otros enlaces del menú */
    .inline-flex, button, a {
        text-decoration: none !important;
    }
</style>
@endpush
