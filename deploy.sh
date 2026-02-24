#!/bin/bash

# Script de despliegue para Hostinger
# Este script debe ejecutarse en el servidor

echo "=== Iniciando despliegue ==="

# Navegar al directorio del proyecto
cd ~/domains/manager.pickntruck.com/public_html || exit

# Instalar dependencias de PHP
echo "Instalando dependencias de Composer..."
composer install --optimize-autoloader --no-dev

# Configurar permisos
echo "Configurando permisos..."
chmod -R 775 storage bootstrap/cache
chown -R $USER:$USER storage bootstrap/cache

# Verificar si existe .env
if [ ! -f .env ]; then
    echo "Copiando archivo .env..."
    cp .env.example .env
    php artisan key:generate
fi

# Ejecutar migraciones
echo "Ejecutando migraciones..."
php artisan migrate --force

# Ejecutar seeders (opcional, comenta si no quieres)
# echo "Ejecutando seeders..."
# php artisan db:seed --force

# Limpiar y optimizar cache
echo "Optimizando aplicación..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Optimización de autoload
composer dump-autoload --optimize

echo "=== Despliegue completado ==="
echo "No olvides configurar tu archivo .env con los datos de la base de datos"
