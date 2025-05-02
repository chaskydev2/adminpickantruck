<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta http-equiv="Content-Security-Policy" content="script-src 'self' 'unsafe-inline' 'unsafe-eval' https:; object-src 'self'">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Favicon -->
        <x-favicon-meta />

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        
        <!-- Font Awesome para iconos -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        
        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        
        <!-- CSS personalizado para animaciones -->
        <link rel="stylesheet" href="{{ asset('css/truck-animation.css') }}">
        
        <!-- CSS para corrección de modales -->
        <link rel="stylesheet" href="{{ asset('css/modal-fix.css') }}">
        
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- Añadir Bootstrap directamente para garantizar su disponibilidad -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
        
        <!-- Chart.js para gráficas -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
        
        <!-- Estilos globales de la aplicación -->
        <style>
            body {
                background-color: #1A202C !important;
                color: #333333;
                font-family: 'Montserrat', sans-serif !important;
            }
            .bg-gray-100 {
                --tw-bg-opacity: 1;
                background-color: #1A202C !important;
            }
            .min-h-screen {
                background-color: #1A202C !important;
            }
            .bg-white {
                --tw-bg-opacity: 1;
                background-color: #ffffff !important;
            }
            .card {
                background-color: #ffffff !important;
                border-color: #e5e7eb !important;
                color: #333333 !important;
                margin-bottom: 1.5rem;
            }
            .card-header {
                background-color: #ffffff !important;
                border-color: #e5e7eb !important;
                padding: 1.25rem 1.5rem !important;
            }
            .card-body {
                color: #333333 !important;
                padding: 1.5rem !important;
            }
            .text-gray-800 {
                --tw-text-opacity: 1;
                color: rgb(31 41 55 / var(--tw-text-opacity)) !important;
            }
            .text-gray-900 {
                --tw-text-opacity: 1;
                color: rgb(17 24 39 / var(--tw-text-opacity)) !important;
            }
            .text-gray-700 {
                --tw-text-opacity: 1;
                color: rgb(55 65 81 / var(--tw-text-opacity)) !important;
            }
            .text-gray-600 {
                --tw-text-opacity: 1;
                color: rgb(75 85 99 / var(--tw-text-opacity)) !important;
            }
            .border-gray-300 {
                --tw-border-opacity: 1;
                border-color: rgb(209 213 219 / var(--tw-border-opacity)) !important;
            }
            .shadow {
                --tw-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1) !important;
                --tw-shadow-colored: 0 1px 3px 0 var(--tw-shadow-color), 0 1px 2px -1px var(--tw-shadow-color) !important;
                box-shadow: var(--tw-ring-offset-shadow, 0 0 #0000), var(--tw-ring-shadow, 0 0 #0000), var(--tw-shadow) !important;
            }
            .table {
                color: #333333 !important;
            }
            .table-hover tbody tr:hover {
                background-color: rgba(0, 0, 0, 0.04) !important;
            }
            .bg-success, .bg-danger, .bg-warning, .bg-info, .bg-primary {
                opacity: 1 !important;
            }
            .bg-theme-header {
                background-color: #1A202C !important;
            }
            .bg-opacity-10 {
                background-opacity: 0.1 !important;
            }
            .icon-box.bg-success, .icon-box.bg-warning, .icon-box.bg-info, .icon-box.bg-primary, .icon-box.bg-dark {
                opacity: 1 !important;
            }
            .card h1, .card h2, .card h3, .card h4, .card h5, .card h6, 
            .card .card-title, .card .text-muted, .list-group-item {
                color: #333333 !important;
            }
            .text-muted {
                color: #6c757d !important;
            }
            header .btn-outline-primary {
                color: #f7fafc !important;
                border-color: #4299e1 !important;
            }
            header .btn-outline-primary:hover {
                background-color: #4299e1 !important;
                color: white !important;
            }
            header .btn-primary {
                background-color: #4299e1 !important;
                border-color: #4299e1 !important;
            }
            .dropdown-content {
                background-color: white !important;
                border: 1px solid #e5e7eb !important;
                color: #333333 !important;
            }
            .dropdown-link {
                color: #374151 !important;
            }
            .dropdown-link:hover {
                background-color: #f3f4f6 !important;
                color: #111827 !important;
            }
            .dropdown-content div a, 
            .dropdown-content div button,
            .dropdown-content div form button {
                color: #374151 !important;
            }
            .dropdown-menu-centered {
                transform: translateX(-50%) !important;
                left: 50% !important;
                right: auto !important;
            }
            .text-white-nav,
            [class*="text-white"],
            nav button,
            nav button div,
            .inline-flex div {
                color: white !important;
            }
            nav .inline-flex {
                color: white !important;
            }
            button[aria-controls="mobile-menu"] {
                color: white !important;
            }
            nav .text-sm, 
            nav .font-medium, 
            nav .leading-4, 
            nav button div {
                color: white !important;
            }
            .dropdown-content {
                background-color: #1A202C !important;
                border: 1px solid #4a5568 !important;
                color: white !important;
            }
            .dropdown-link {
                color: white !important;
            }
            .dropdown-link:hover {
                background-color: #2d3748 !important;
                color: white !important;
            }
            .dropdown-content div a, 
            .dropdown-content div button,
            .dropdown-content div form button {
                color: white !important;
            }
            .dropdown-container {
                right: 0 !important;
                left: auto !important;
                transform: none !important;
            }
            #profile-dropdown .dropdown-container {
                right: 0 !important;
                left: auto !important;
            }
            .container {
                padding-left: 1.5rem !important;
                padding-right: 1.5rem !important;
                max-width: 1400px !important;
            }
            main {
                padding: 0.5rem;
            }
            .card {
                margin-bottom: 1.5rem;
            }
            .card-body {
                padding: 1.5rem !important;
            }
            .card-header {
                padding: 1.25rem 1.5rem !important;
            }
            .row {
                margin-bottom: 1rem;
            }
            .mb-4 {
                margin-bottom: 1.5rem !important;
            }
            .mb-5 {
                margin-bottom: 2rem !important;
            }
            @media (max-width: 768px) {
                .container {
                    padding-left: 1rem !important;
                    padding-right: 1rem !important;
                }
                .card-body {
                    padding: 1.25rem !important;
                }
            }
            h1, h2, h3, h4, h5, h6,
            p, span, div, a, button,
            input, textarea, select,
            table, th, td, label, li {
                font-family: 'Montserrat', sans-serif !important;
            }
            h1, h2, h3, .card-title {
                font-weight: 600 !important;
            }
            h4, h5, h6 {
                font-weight: 500 !important;
            }
            p, div, td {
                font-weight: 400 !important;
            }
            .text-muted, small {
                font-weight: 300 !important;
            }
            a, button, .nav-link, .dropdown-link {
                text-decoration: none !important;
            }
            a:hover, button:hover, .nav-link:hover, .dropdown-link:hover {
                text-decoration: none !important;
            }
            .logo-container {
                display: flex;
                align-items: center;
                gap: 10px;
            }
            .logo-icon {
                display: flex;
                align-items: center;
                justify-content: center;
                height: 40px;
                width: 40px;
            }
            .logo-text {
                display: flex;
                align-items: center;
            }
            @media (max-width: 640px) {
                .logo-text {
                    display: none;
                }
            }
        </style>
        
        @stack('styles')
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-dark-theme">
            @include('layouts.navigation')
            
            <!-- Page Heading -->
            @isset($header)
                <header class="bg-theme-header shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset
            
            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
        
        <!-- Bootstrap Bundle with Popper -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        
        <!-- Fix para modales de Bootstrap -->
        <script src="{{ asset('js/modal-fix.js') }}"></script>
        
        <!-- Script para animaciones -->
        <script src="{{ asset('js/truck-animation.js') }}"></script>
        
        <!-- Script para centrar dropdowns -->
        <script src="{{ asset('js/dropdown-center.js') }}"></script>
        
        <!-- Script principal -->
        <script src="{{ asset('js/main.js') }}"></script>
        
        @stack('scripts')
    </body>
</html>