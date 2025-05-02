<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

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
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
        
        <!-- Scripts -->        
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Estilos globales de autenticación -->
        <style>
            /* Estilos globales para páginas de autenticación */
            body {
                background-color: #1A202C !important;
                color: #333333;
                font-family: 'Montserrat', sans-serif !important;
            }
            
            .min-h-screen {
                background-color: #1A202C !important;
                padding: 1.5rem;
            }
            
            .bg-gray-100 {
                background-color: #1A202C !important;
            }
            
            .text-gray-900 {
                color: #333333 !important;
            }
            
            .text-gray-600, .text-gray-700, .text-gray-800 {
                color: #4b5563 !important;
            }
            
            /* Ajustes para inputs y botones */
            input:focus {
                border-color: #3b82f6 !important;
                box-shadow: 0 0 0 .25rem rgba(59, 130, 246, .25) !important;
            }
            
            .btn-primary {
                background-color: #4299e1 !important;
                border-color: #4299e1 !important;
            }
            
            .btn-primary:hover {
                background-color: #3182ce !important;
                border-color: #3182ce !important;
            }
            
            /* Quitar subrayado de enlaces */
            a {
                text-decoration: none !important;
            }
            
            a:hover {
                text-decoration: none !important;
            }
            
            /* Margenes y espaciados */
            .py-5 {
                padding-top: 3rem !important;
                padding-bottom: 3rem !important;
            }
            
            /* Card de autenticación */
            .auth-card {
                border-radius: 0.75rem !important;
                overflow: hidden;
                box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06);
                background-color: white !important;
                border: none !important;
            }
            
            /* Logo en páginas de autenticación */
            .auth-logo {
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 10px;
                margin-bottom: 20px;
            }
            
            .auth-logo .logo-icon {
                font-size: 2.5rem;
                color: #4299e1;
            }
            
            .auth-logo .logo-text {
                font-family: 'Montserrat', sans-serif;
                font-weight: 700;
                font-size: 1.5rem;
                color: white;
            }
            
            .auth-logo .logo-image {
                height: 48px; /* Reducido de 60px (un 20% menos) */
                width: auto;
            }
        </style>
        @stack('styles')
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
            <div class="w-full sm:max-w-md mt-6">
                {{ $slot }}
            </div>
        </div>
        <!-- Bootstrap Bundle with Popper -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
        
        <!-- Script para animaciones -->
        <script src="{{ asset('js/truck-animation.js') }}"></script>
         
        @stack('scripts') 
    </body>
</html>
