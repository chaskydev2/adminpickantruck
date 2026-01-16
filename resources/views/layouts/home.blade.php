<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Panel de Control')</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}" />

    <!-- Vite CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Bootstrap y Font Awesome (solo si no están incluidos en app.css) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        :root {
            --sidebar-width: 250px;
            --header-height: 56px; /* Ajusta esta altura si tu header tiene una altura diferente */
            --content-padding: 20px; /* Un padding general para el contenido */
        }

        body {
            min-height: 100vh;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            overflow-x: hidden; /* Evitar scroll horizontal indeseado */
        }

        /* Estilos del Header Fijo */
        .main-header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: var(--header-height);
            z-index: 1030;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,.05);
        }

        /* Estilos del Sidebar Fijo */
        .sidebar {
            width: var(--sidebar-width);
            height: calc(100vh - var(--header-height)); /* Ocupa el espacio restante bajo el header */
            position: fixed;
            top: var(--header-height); /* Inicia justo debajo del header */
            left: 0;
            z-index: 1020;
            background: linear-gradient(180deg, #142e54 0%, #0c1f3d 100%);
            padding: 1rem 0;
            overflow-y: auto;
            transition: transform 0.3s ease-in-out;
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 10px 15px;
            border-radius: 4px;
            margin: 2px 15px;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
            text-decoration: none;
        }

        /* Contenido Principal (Page Content) - AJUSTE CLAVE AQUÍ */
        .main-content {
            /* Desplaza el contenido a la derecha del sidebar */
            margin-left: var(--sidebar-width); 
            /* Desplaza el contenido hacia abajo para dejar espacio al header */
            margin-top: var(--header-height); 
            /* Añade padding interno al contenido */
            padding: 1.5rem; 
            min-height: calc(100vh - var(--header-height));
            box-sizing: border-box;
            transition: margin-left 0.3s ease-in-out;
            position: relative;
            z-index: 1;
            /* overflow-y: auto; */
            /* max-height: calc(100vh - var(--header-height)); */
        }
        
        /* Ajustes para el contenido dentro del dashboard */
        .dashboard-content {
            max-width: 1400px;
            margin: 0 auto;
            width: 100%;
        }

        /* Estilos para móviles */
        @media (max-width: 991.98px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }
            
            .sidebar.show + .main-content::before {
                content: '';
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: rgba(0,0,0,0.5);
                z-index: 1010;
            }
        }
    </style>
    @stack('styles') {{-- Para estilos específicos de cada vista --}}
</head>
<body>
    <header class="main-header">
        @include('layouts.header')
    </header>

    <aside class="sidebar">
        @include('layouts.navbar')
    </aside>

    <main class="main-content">
        <div class="container-fluid">
            @yield('content')
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.createElement('button');
            sidebarToggle.className = 'btn btn-link text-white d-lg-none me-3';
            sidebarToggle.innerHTML = '<i class="fas fa-bars"></i>';
            sidebarToggle.onclick = function() {
                document.querySelector('.sidebar').classList.toggle('show');
            };

            const headerNavbar = document.querySelector('.main-header .navbar'); 
            if (headerNavbar) {
                headerNavbar.prepend(sidebarToggle);
            } else {
                const headerContent = document.querySelector('.main-header');
                if (headerContent) {
                    headerContent.insertBefore(sidebarToggle, headerContent.firstChild);
                }
            }

            const navLinks = document.querySelectorAll('.sidebar .nav-link');
            navLinks.forEach(link => {
                link.addEventListener('click', function() {
                    if (window.innerWidth < 992) {
                        document.querySelector('.sidebar').classList.remove('show');
                    }
                });
            });
        });
    </script>
    @stack('scripts') {{-- Para scripts específicos de cada vista --}}
</body>
</html>