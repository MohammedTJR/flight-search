#!/usr/bin/env bash
#
# Script de despliegue de FlyLow en producción.
# Lo ejecuta GitHub Actions por SSH tras cada push a main
# (después de hacer `git pull`). También se puede lanzar a mano.
#
set -euo pipefail

cd /var/www/flight-search

echo "==> Rama y commit actual"
git log --oneline -1

# Dependencias PHP (solo si composer está disponible)
if command -v composer >/dev/null 2>&1; then
    echo "==> composer install"
    composer install --no-dev --optimize-autoloader --no-interaction
else
    echo "!! composer no encontrado, se omite (instálalo si cambian dependencias PHP)"
fi

# Dependencias Node + compilación de assets (vite es devDependency, por eso se instala todo)
echo "==> npm ci + build"
npm ci
npm run build

# Migraciones de base de datos (si las hay)
echo "==> migraciones"
php artisan migrate --force

# Cachés de Laravel
echo "==> cachés"
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Recargar PHP-FPM para limpiar opcache y servir el código nuevo
echo "==> recargar php-fpm"
systemctl reload php8.3-fpm 2>/dev/null || true

echo "==> DESPLIEGUE COMPLETADO"
