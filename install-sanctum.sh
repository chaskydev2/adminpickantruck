#!/bin/bash

# Este script instala Laravel Sanctum si es necesario

echo "Instalando Laravel Sanctum..."
composer require laravel/sanctum

echo "Publicando los archivos de configuración..."
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"

echo "Ejecutando migraciones..."
php artisan migrate

echo "Laravel Sanctum se ha instalado correctamente."
